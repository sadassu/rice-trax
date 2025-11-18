<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::withSum('batches as total_kg_remaining', 'kg_remaining')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByDesc('total_kg_remaining') // prioritize >0
            ->orderByRaw('LOWER(name) ASC')     // alphabetical within each group
            ->paginate(15)
            ->withQueryString();

        $sales = Sale::latest()->take(9)->get();

        return Inertia::render('Pos/Pos', [
            'sales'      => $sales,
            'products'   => $products,
            'searchTerm' => $request->search,
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'total' => ['required', 'decimal:0', 'min:0'],
            'amount_paid' => ['required', 'decimal:0', 'min:0'],
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'total_price' => $request->total,
                'user_id' => Auth::id(),
                'sale_date' => Carbon::now(),
                'amount_paid' => $request->amount_paid,
                'change' => $request->amount_paid - $request->total,
            ]);

            foreach ($request->cart as $product) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['id'],
                    'kilograms' => $product['kilograms'],
                    'price_per_kilo' => $product['price_per_kilo'],
                    'total_price' => $product['total_price'],
                ]);

                $remainingKg = $product['kilograms'];

                $productBatches = ProductBatch::where('product_id', $product['id'])
                    ->where('kg_remaining', '>', 0)
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($productBatches as $batch) {
                    if ($remainingKg <= 0) {
                        break;
                    }

                    if ($batch->kg_remaining >= $remainingKg) {
                        $batch->kg_remaining -= $remainingKg;
                        $batch->save();

                        $remainingKg = 0;
                    } else {
                        $remainingKg -= $batch->kg_remaining;
                        $batch->kg_remaining = 0;
                        $batch->save();
                    }
                }

                if ($remainingKg > 0) {
                    throw new \Exception("Not enough stock for product ID: {$product['id']}");
                }

                // ✅ Check total remaining stock for this product after update
                $totalRemaining = ProductBatch::where('product_id', $product['id'])->sum('kg_remaining');

                if ($totalRemaining <= 100) {
                    Notification::create([
                        'title' => 'Low Stock Alert',
                        'message' => 'Product "' . $product['name'] . '" is low on stock (' . $totalRemaining . ' kg remaining).',
                        'recipient_role' => 'admin',
                        'expires_at' => Carbon::now()->addDays(7),
                    ]);
                }
            }

            // Log the sale activity
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'event'      => 'created',
                'module'     => 'sales',
                'description' => 'Created sale: ' . $sale->sale_date,
                'properties' => ['sale_id' => $sale->id],
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);

            DB::commit();

            return redirect()->back()
                ->with('message', 'Sale recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Checkout failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while saving the sale. Please try again.');
        }
    }

    public function receipt(Sale $sale)
    {
        // Eager load sale details and their products
        $sale->load(['saleDetails.product']);

        return Inertia::render('Pos/Receipt', [
            'sale' => $sale,
        ]);
    }
}
