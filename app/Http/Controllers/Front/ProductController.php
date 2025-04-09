<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ExchangeRateService;

class ProductController extends Controller
{
    protected $exchangeRate;

    public function __construct(ExchangeRateService $exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }
    
    public function index()
    {
        $products = Product::all();
        $exchangeRate = $this->exchangeRate->getRate();

        return view('front.product.list', compact('products', 'exchangeRate'));
    }

    public function show(Product $product)
    {
        
        $exchangeRate = $this->exchangeRate->getRate();
        return view('front.product.show', compact('product', 'exchangeRate'));
    }
}
