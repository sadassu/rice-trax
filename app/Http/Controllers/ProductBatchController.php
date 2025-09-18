<?php

namespace App\Http\Controllers;

use App\Models\ProductBatch;
use App\Http\Requests\StoreProductBatchRequest;
use App\Http\Requests\UpdateProductBatchRequest;
use App\Models\ActivityLog;
use App\Models\Product;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ProductBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* this need to be update as have search and a to z*/
        return inertia(
            'ProductBatches/ProductBatches',
            [
                'product_batches' => ProductBatch::all(),
                'products' => Product::all(),
            ]
        );
    }

    public function showBatchesByProduct(Product $product)
    {
        $batches = $product->batches()->orderBy('created_at', 'desc')->get();

        return inertia('ProductBatches/ShowBatches', [
            'product' => $product,
            'productBatches' => $batches,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductBatchRequest $request)
    {
        $data = $request->validated();

        // Get the product
        $product = Product::findOrFail($data['product_id']);

        // Generate batch number with product name
        // Example: "PB-Rice-1"
        $batchCount = ProductBatch::where('product_id', $product->id)->count();
        $batchNumber =  Str::slug($product->name) . '-' . ($batchCount + 1);

        $data['batch_number'] = $batchNumber;
        $data['kg_remaining'] = $request->input('sack') * 25;

        $batch = ProductBatch::create($data);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'created',
            'module'     => 'product_batch',
            'description' => 'Created product batch: ' . $batch->batch_number . ' for ' . $batch->product_id,
            'properties' => ['product_batch_id' => $batch->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->back()
            ->with('message', "Product batch for {$batch->batch_number} created successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductBatch $productBatch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductBatch $productBatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductBatchRequest $request, ProductBatch $productBatch)
    {
        $productBatch->update($request->validated());

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'event'      => 'updated',
            'module'     => 'product_batch',
            'description' => 'Updated product batch: ' . $productBatch->batch_number,
            'properties' => ['product_batch_id' => $productBatch->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return redirect()->back()
            ->with('message', "Product batch for {$productBatch->batch_number} updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductBatch $productBatch)
    {
        $productBatch->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'event'       => 'deleted',
            'module'      => 'products',
            'description' => 'Deleted product: ' . $productBatch->name,
            'properties'  => ['product_batch_id' => $productBatch->id],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->header('User-Agent'),
        ]);

        return redirect()->back()
            ->with('message', "Product batch for {$productBatch->batch_number} deleted successfully!");
    }
}
