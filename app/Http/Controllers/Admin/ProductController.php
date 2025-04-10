<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use App\Jobs\SendPriceChangeNotification;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Cache::remember('admin_products_all', 3600, function () {
            return Product::all();
        });
        return view('admin.product.list', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        try {
            $data['image'] = $this->imageUploadService->uploadImage($request->file('image'));
        } catch (\Exception $e) {
            Log::error('Failed to create product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to create product. Please try again.');
        }

        $product = Product::create($data);
        $this->refreshCaches();

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $oldPrice = $product->price;
            $data = $request->validated();
            $data['image'] = $this->imageUploadService->uploadImage($request->file('image'), $product->image);

            $product->update($data);

            if ($oldPrice != $product->price) {
                $notificationEmail = config('app.price_notification_email');
                try {
                    SendPriceChangeNotification::dispatch($product, $oldPrice, $product->price, $notificationEmail);
                } catch (\Exception $e) {
                    Log::error('Failed to dispatch price change notification: ' . $e->getMessage());
                }
            }

            $this->refreshCaches();
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
        } catch (ModelNotFoundException $e) {
            Log::warning('Product not found for update: ' . $e->getMessage());
            return redirect()->route('admin.products.index')->with('error', 'Product not found.');
        } catch (\Exception $e) {
            Log::error('Failed to update product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to update product. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if ($product->image && $product->image !== 'product-placeholder.jpg') {
                $this->imageUploadService->deleteImage(basename($product->image));
            }

            $product->delete();
            $this->refreshCaches();
            return redirect()->back()->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to delete product. Please try again.');
        }
    }
    protected function refreshCaches() {
        try {
			Cache::forget('admin_products_all');
			Cache::forget('front_products_all');
		} catch (\Exception $e) {
			Log::error('Failed to refresh caches: ' . $e->getMessage());
		}
    }
}
