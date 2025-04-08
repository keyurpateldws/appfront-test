<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\StoreProductRequest;
use App\Http\Requests\Backend\UpdateProductRequest;
use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
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

        $requestData = $request->validated();
        $product = Product::create($requestData);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->image = 'uploads/' . $filename;
        } else {
            $product->image = 'product-placeholder.jpg';
        }

        $product->save();
        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        return view('admin.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::find($id);

        // Store the old price before updating
        $oldPrice = $product->price;

        $product->update($request->all());

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->image = 'uploads/' . $filename;
        }

        $product->save();

        // Check if price has changed
        if ($oldPrice != $product->price) {
            // Get notification email from env
            $notificationEmail = env('PRICE_NOTIFICATION_EMAIL', 'admin@example.com');

            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $oldPrice,
                    $product->price,
                    $notificationEmail
                );
            } catch (\Exception $e) {
                Log::error('Failed to dispatch price change notification: ' . $e->getMessage());
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
