<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Upload a product file to private storage
     */
    public function uploadProductFile(UploadedFile $file): string
    {
        $timestamp = time();
        $filename = $timestamp . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        
        $path = 'products/' . $filename;
        
        Storage::disk('private')->putFileAs('products', $file, $filename);
        
        return $path;
    }

    /**
     * Delete a product file from private storage
     */
    public function deleteProductFile(string $path): bool
    {
        if (Storage::disk('private')->exists($path)) {
            return Storage::disk('private')->delete($path);
        }
        
        return false;
    }

    /**
     * Delete any file from private storage (alias for deleteProductFile)
     */
    public function deleteFile(string $path): bool
    {
        return $this->deleteProductFile($path);
    }

    /**
     * Check if file exists
     */
    public function fileExists(string $path): bool
    {
        return Storage::disk('private')->exists($path);
    }

    /**
     * Get file path for download
     */
    public function getFilePath(string $path): string
    {
        return Storage::disk('private')->path($path);
    }

    /**
     * Upload an image file to public storage
     */
    public function uploadImage(UploadedFile $file, string $directory = 'images'): string
    {
        $timestamp = time();
        $filename = $timestamp . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        
        $path = $directory . '/' . $filename;
        
        Storage::disk('public')->putFileAs($directory, $file, $filename);
        
        return $path;
    }

    /**
     * Delete an image file from public storage
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
}
