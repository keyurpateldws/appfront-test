<?php

namespace App\Handlers;

use App\Commands\UpdateProductCommand;
use App\Models\Product;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UpdateProductCommandHandler
{
    public function handle(UpdateProductCommand $command): void
    {
        $product = Product::findOrFail($command->id);
        $oldPrice = $product->price;
        
        $data = [];
        
        // Validate and process name
        if ($command->name !== null) {
            $validator = Validator::make(
                ['name' => $command->name],
                ['name' => 'required|string|min:3']
            );
            
            if ($validator->fails()) {
                throw new \InvalidArgumentException($validator->errors()->first());
            }
            
            $data['name'] = $command->name;
        }
        
        // Process description
        if ($command->description !== null) {
            $data['description'] = $command->description;
        }
        
        // Validate and process price
        if ($command->price !== null) {
            $validator = Validator::make(
                ['price' => $command->price],
                ['price' => 'required|numeric|min:0']
            );
            
            if ($validator->fails()) {
                throw new \InvalidArgumentException($validator->errors()->first());
            }
            
            $data['price'] = $command->price;
        }

        if (!empty($data)) {
            $product->update($data);

            // Check if price has changed
            if (isset($data['price']) && $oldPrice != $product->price) {
                $notificationEmail = config('app.price_notification_email');
                try {
                    SendPriceChangeNotification::dispatch(
                        $product,
                        $oldPrice,
                        $product->price,
                        $notificationEmail
                    );
                } catch (\Exception $e) {
                    Log::error("Failed to dispatch price change notification: " . $e->getMessage());
                }
            }
        }
    }
} 