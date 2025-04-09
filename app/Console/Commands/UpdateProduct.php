<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\Log;

class UpdateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update {id} {--name=} {--description=} {--price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a product with the specified details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        $product = Product::find($id);

        if (!$product) {
            $this->error("Product with ID {$id} not found.");
            return 1;
        }
        
        $data = [];
        if ($this->option('name')) {
            $data['name'] = $this->option('name');
            if (empty($data['name']) || trim($data['name']) == '') {
                $this->error("Name cannot be empty.");
                return 1;
            }
            if (strlen($data['name']) < 3) {
                $this->error("Name must be at least 3 characters long.");
                return 1;
            }
        }
        if ($this->option('description')) {
            $data['description'] = $this->option('description');
        }
        if ($this->option('price')) {
            $data['price'] = $this->option('price');
        }

        $oldPrice = $product->price;

        if (!empty($data)) {
            $product->update($data);
            $product->save();

            $this->info("Product updated successfully.");

            // Check if price has changed
            if (isset($data['price']) && $oldPrice != $product->price) {
                $this->info("Price changed from {$oldPrice} to {$product->price}.");

                $notificationEmail = env('PRICE_NOTIFICATION_EMAIL', 'admin@example.com');

                try {
                    SendPriceChangeNotification::dispatch(
                        $product,
                        $oldPrice,
                        $product->price,
                        $notificationEmail
                    );
                    $this->info("Price change notification dispatched to {$notificationEmail}.");
                } catch (\Exception $e) {
                    $this->error("Failed to dispatch price change notification: " . $e->getMessage());
                }
            }
        } else {
            $this->info("No changes provided. Product remains unchanged.");
        }

        return 0;
    }
}
