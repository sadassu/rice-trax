<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('batches')
            ->withSum('batches', 'kg_remaining')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderByRaw('LOWER(name) ASC')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render(
            'Products/ShowProducts',
            [
                'products'   => $products,
                'searchTerm' => $request->search,
            ]
        );
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'created',
            'module'     => 'products',
            'description' => 'Created product: ' . $product->name,
            'properties' => ['product_id' => $product->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
        return redirect()
            ->route('products.index')
            ->with([
                'product' => $product,
                'message' => 'Product created successfully!',
                'created_product_id' => $product->id,
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Get all sale details of this product with their parent sale
        $sales = $product->saleDetail()
            ->with('sale')
            ->get();

        // 🔹 Daily sales (group by sale_date)
        $dailySales = $sales->groupBy(
            fn($detail) =>
            Carbon::parse($detail->sale->sale_date)->format('Y-m-d')
        )->map(fn($group) => $group->sum('total_price'));

        // 🔹 Monthly sales (group by year-month)
        $monthlySales = $sales->groupBy(
            fn($detail) =>
            Carbon::parse($detail->sale->sale_date)->format('Y-m')
        )->map(fn($group) => $group->sum('total_price'));

        // 🔹 Load product batches
        $product->load('batches');

        // 🔹 Compute total kg remaining
        $totalKgRemaining = $product->batches->sum('kg_remaining');

        // 🔹 Compute total inventory value
        $totalInventoryValue = $totalKgRemaining * $product->price_per_kilo;

        // 🔹 Map sales for table display
        $salesTable = $sales->map(fn($detail) => [
            'id' => $detail->id,
            'sale_date' => $detail->sale?->sale_date,
            'kilograms' => $detail->kilograms,
            'price_per_kilo' => $detail->price_per_kilo,
            'total_price' => $detail->total_price,
        ])->sortByDesc('sale_date')->values();

        return Inertia::render('Products/SpecificProduct', [
            'product' => $product,
            'dailySales' => $dailySales,
            'monthlySales' => $monthlySales,
            'totalKgRemaining' => $totalKgRemaining,
            'totalInventoryValue' => $totalInventoryValue,
            'salesTable' => $salesTable,
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/UpdateProduct', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'updated',
            'module'     => 'products',
            'description' => 'Updated product: ' . $product->name,
            'properties' => ['product_id' => $product->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->back()
            ->with('message', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'deleted',
            'module'      => 'products',
            'description' => 'Deleted product: ' . $product->name,
            'properties'  => ['product_id' => $product->id],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->header('User-Agent'),
        ]);

        return redirect()->back()
            ->with('message', 'Product deleted successfully!');
    }
}
