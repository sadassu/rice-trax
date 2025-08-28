<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Count of products
        $productsCount = Product::count();

        // Total of all sales (assuming sales table has total_price column)
        $salesTotal = Sale::sum('total_price');

        return Inertia::render('Home', [
            'products' => $productsCount,
            'sale'     => $salesTotal,
        ]);
    }
}
