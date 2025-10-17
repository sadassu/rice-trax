<?php

namespace App\Imports;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            $grouped = $rows->groupBy('sale_id');

            foreach ($grouped as $saleId => $details) {
                $first = $details->first();

                $sale = Sale::create([
                    'total_price' => $first['total_sale_price'] ?? 0,
                    'sale_date' => Carbon::parse($first['sale_date']),
                    'amount_paid' => $first['amount_paid'] ?? 0,
                    'change' => $first['change'] ?? 0,
                ]);

                foreach ($details as $row) {
                    $product = Product::whereRaw('LOWER(name) = ?', [strtolower(trim($row['product_name']))])->first();

                    if (!$product) {
                        continue;
                    }

                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'kilograms' => $row['kilograms'] ?? 0,
                        'price_per_kilo' => $row['price_per_kilo'] ?? 0,
                        'total_price' => $row['detail_total_price'] ?? 0,
                    ]);
                }
            }
        });
    }
}
