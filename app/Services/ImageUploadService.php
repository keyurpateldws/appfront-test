<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    protected $uploadPath;
    protected $defaultImage;
    protected string $disk;
    
    public function __construct(
        string $uploadPath = 'uploads',
        string $defaultImage = 'product-placeholder.jpg',
        string $disk = 'public'
    ) {
        $this->uploadPath = $uploadPath;
        $this->defaultImage = $defaultImage;
        $this->disk = $disk;
    }

    public function uploadImage(?UploadedFile $file, ?string $currentImage = null): string
    {
        if (!$file) {
            return $currentImage ?? $this->defaultImage;
        }

        try {
            // Generate a unique filename
            $filename = Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($this->uploadPath, $filename, $this->disk);

            // Delete old image if it exists and isn’t default
            if ($currentImage && $currentImage !== $this->defaultImage) {
                $this->deleteImage($currentImage);
            }

            return basename($path);
        } catch (FileNotFoundException $e) {
            Log::error('Image upload failed due to file issue', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);
            return $currentImage ?? $this->defaultImage;
        } catch (\Exception $e) {
            Log::error('Image upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);
            return $currentImage ?? $this->defaultImage;
        }
    }

    public function deleteImage(string $filename): bool
    {
        if ($filename === $this->defaultImage || empty($filename)) {
            return true; // No action needed
        }

        $filePath = $this->uploadPath . '/' . $filename;

        try {
            if (Storage::disk($this->disk)->exists($filePath)) {
                return Storage::disk($this->disk)->delete($filePath);
            }
            return true; // File doesn’t exist, consider it "deleted"
        } catch (\Exception $e) {
            Log::warning('Failed to delete image', [
                'file' => $filePath,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
