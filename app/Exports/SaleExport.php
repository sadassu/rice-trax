<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaleExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Filter sales between the given dates
        $sales = Sale::with(['saleDetails.product'])
            ->whereBetween('sale_date', [$this->startDate, $this->endDate])
            ->get();

        $rows = [];

        foreach ($sales as $sale) {
            foreach ($sale->saleDetails as $detail) {
                $rows[] = [
                    'Sale ID' => $sale->id,
                    'Sale Date' => $sale->sale_date->format('Y-m-d H:i:s'),
                    'Total Sale Price' => $sale->total_price,
                    'Amount Paid' => $sale->amount_paid,
                    'Change' => $sale->change,
                    'Product Name' => $detail->product->name ?? 'N/A',
                    'Kilograms' => $detail->kilograms,
                    'Price per Kilo' => $detail->price_per_kilo,
                    'Detail Total Price' => $detail->total_price,
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Sale ID',
            'Sale Date',
            'Total Sale Price',
            'Amount Paid',
            'Change',
            'Product Name',
            'Kilograms',
            'Price per Kilo',
            'Detail Total Price',
        ];
    }
}
