<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    protected $uploadPath;
    protected $defaultImage;

    public function __construct(string $uploadPath = 'uploads', string $defaultImage = 'product-placeholder.jpg')
    {
        $this->uploadPath = $uploadPath;
        $this->defaultImage = $defaultImage;
    }

    public function uploadImage(UploadedFile $file = null, ?string $currentImage = null, ?string $customPath = null): string
    {
        if (!$file) {
            return $currentImage ?? $this->defaultImage;
        }

        try {
            $path = $customPath ?? $this->uploadPath;
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $filename);

            // Delete old image if it exists and is not the default
            if ($currentImage && $currentImage !== $this->defaultImage) {
                $oldImagePath = public_path($path . '/' . $currentImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            return $filename;
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return $currentImage ?? $this->defaultImage;
        }
    }

    public function deleteImage(string $filename, ?string $customPath = null)
    {
        if ($filename && $filename !== $this->defaultImage) {
            $path = $customPath ?? $this->uploadPath;
            $imagePath = public_path($path . '/' . $filename);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }
}