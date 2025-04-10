<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ExchangeRateService;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{

    public function __construct(protected ExchangeRateService $exchangeRate) {}

    public function index()
    {
        try {
            $products = Cache::remember('front_products_all', 3600, function () {
                return Product::all(); // Could later be Product::where('active', true)->get()
            });

            $exchangeRate = $this->exchangeRate->getRate();

            return view('front.product.list', compact('products', 'exchangeRate'));
        } catch (\Exception $e) {
            Log::error('Failed to load product index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('front.products.index')->with('error', 'Unable to load products. Please try again later.');
        }
    }

    public function show(Product $product)
    {
        try {
            $exchangeRate = $this->exchangeRate->getRate();
            return view('front.product.show', compact('product', 'exchangeRate'));
        } catch (\Exception $e) {
            Log::error('Failed to load product details', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('front.products.index')->with('error', 'Unable to load product details. Please try again.');
        }
    }
}
