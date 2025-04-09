<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // if you want to use the factory to create products
        // Product::factory()->count(10)->create();

        // if you want to use static data to create products
        // Clear existing products
        DB::table('products')->truncate();

        // Sample product data
        $products = [
            [
                'name' => 'Professional DSLR Camera',
                'description' => 'High-end digital camera with 24.2MP sensor, 4K video recording, and advanced autofocus system. Perfect for professional photographers and videographers.',
                'price' => 1299.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wireless Noise-Cancelling Headphones',
                'description' => 'Premium wireless headphones with active noise cancellation, 30-hour battery life, and comfortable over-ear design for immersive audio experience.',
                'price' => 249.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ultra-Thin Laptop',
                'description' => 'Powerful and lightweight laptop featuring a 13-inch Retina display, 16GB RAM, 512GB SSD, and all-day battery life. Ideal for professionals on the go.',
                'price' => 1499.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smart Fitness Watch',
                'description' => 'Advanced fitness tracker with heart rate monitoring, GPS, sleep tracking, and 7-day battery life. Water-resistant and compatible with iOS and Android.',
                'price' => 199.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ergonomic Office Chair',
                'description' => 'Comfortable office chair with adjustable height, lumbar support, and breathable mesh back. Designed for long hours of work with minimal fatigue.',
                'price' => 299.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Portable Bluetooth Speaker',
                'description' => 'Compact and powerful Bluetooth speaker with 360° sound, 12-hour playtime, and waterproof design. Perfect for outdoor adventures and parties.',
                'price' => 89.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional Blender',
                'description' => 'High-performance blender with variable speed control, pulse function, and durable stainless steel blades. Ideal for smoothies, soups, and food preparation.',
                'price' => 179.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smart Home Security Camera',
                'description' => 'HD security camera with night vision, motion detection, two-way audio, and cloud storage. Monitor your home from anywhere using the smartphone app.',
                'price' => 129.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'Premium mechanical keyboard with RGB backlighting, programmable keys, and durable construction. Designed for gamers and typing enthusiasts.',
                'price' => 149.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electric Coffee Grinder',
                'description' => 'Precision coffee grinder with multiple grind settings, stainless steel burrs, and large capacity. Perfect for coffee enthusiasts seeking the perfect brew.',
                'price' => 69.99,
                'image' => 'product-placeholder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert products into the database
        Product::insert($products);
        $this->command->info('10 sample products seeded successfully!');
    }
}
