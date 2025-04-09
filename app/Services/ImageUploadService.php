<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    protected $uploadPath;
    protected $defaultImage;

    public function __construct(string $uploadPath = 'uploads', string $defaultImage = 'product-placeholder.jpg')
    {
        $this->uploadPath = $uploadPath;
        $this->defaultImage = $defaultImage;
    }

    public function uploadImage(UploadedFile $file = null, ?string $currentImage = null): string
    {
        if (!$file) {
            return $currentImage ?? $this->defaultImage;
        }

        try {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($this->uploadPath, $filename, 'public');

            // Delete old image if exists and not default
            if ($currentImage && $currentImage !== $this->defaultImage) {
                $this->deleteImage($currentImage);
            }

            return basename($path);
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return $currentImage ?? $this->defaultImage;
        }
    }

    public function deleteImage(string $filename)
    {
        if ($filename && $filename !== $this->defaultImage) {
            $filePath = $this->uploadPath . '/' . $filename;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
    }
}