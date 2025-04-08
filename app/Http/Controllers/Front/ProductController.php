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
        try {
            $products = Product::all();
            $exchangeRate = $this->exchangeRate->getRate();

            return view('front.product.list', compact('products', 'exchangeRate'));
        } catch (\Exception $e) {
            Log::error('Failed to load product index: ' . $e->getMessage());
            return redirect()->route('front.products.show')->with('error', 'Unable to load products. Please try again later.');
        }
    }

    public function show(Product $product)
    {
        try {
            $exchangeRate = $this->exchangeRate->getRate();
            return view('front.product.show', compact('product', 'exchangeRate'));
        } catch (\Exception $e) {
            Log::error('Failed to load product show: ' . $e->getMessage());
            return redirect()->route('front.products.show')->with('error', 'Unable to load product details. Please try again.');
        }
    }

    /**
     * @return float
     */
    // private function getExchangeRate()
    // {
    //     try {
    //         $curl = curl_init();

    //         curl_setopt_array($curl, [
    //             CURLOPT_URL => "https://open.er-api.com/v6/latest/USD",
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_TIMEOUT => 5,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => "GET",
    //         ]);

    //         $response = curl_exec($curl);
    //         $err = curl_error($curl);

    //         curl_close($curl);

    //         if (!$err) {
    //             $data = json_decode($response, true);
    //             if (isset($data['rates']['EUR'])) {
    //                 return $data['rates']['EUR'];
    //             }
    //         }
    //     } catch (\Exception $e) {

    //     }

    //     return env('EXCHANGE_RATE', 0.85);
    // }
}
