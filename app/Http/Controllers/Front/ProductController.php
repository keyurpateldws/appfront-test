<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ExchangeRateService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    protected $exchangeRate;

    public function __construct(ExchangeRateService $exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }
    
    public function index()
    {
        try {
            $products = Product::all();
            $exchangeRate = $this->exchangeRate->getRate();

            return view('front.product.list', compact('products', 'exchangeRate'));
        } catch (\Exception $e) {
            Log::error('Failed to load product index: ' . $e->getMessage());
            return redirect()->route('front.products.index')->with('error', 'Unable to load products. Please try again later.');
        }
    }

    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $exchangeRate = $this->exchangeRate->getRate();
            return view('front.product.show', compact('product', 'exchangeRate'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('front.products.index')->with('error', 'Product not found');
        } catch (\Exception $e) {
            Log::error('Failed to load product show: ' . $e->getMessage());
            return redirect()->route('front.products.index')
                ->with('error', 'Unable to load product details. Please try again.');
        }
    }
}
