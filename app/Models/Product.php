<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    // Set a default value for the 'image' attribute
    protected $attributes = [
        'image' => 'product-placeholder.jpg',
    ];

    /**
     * Accessor for the image URL.
     * 
     * Returns the URL to the product image if it exists in storage.
     * Otherwise, returns the default placeholder image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        $imagePath = 'uploads/' . $this->image;

        // Check if the image exists in the public disk storage
        if ($this->image && Storage::disk('public')->exists($imagePath)) {
            return asset('storage/' . $imagePath);
        }

        // Return the default placeholder image located in the public directory
        return asset('product-placeholder.jpg');
    }
}
