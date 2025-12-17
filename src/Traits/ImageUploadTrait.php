<?php

namespace NomanIsmail\MediaPanel\Traits;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait ImageUploadTrait
{
    /**
     * Process and upload image with multiple sizes.
     *
     * @param UploadedFile $file
     * @param string $name
     * @return array
     */
    public function processImage(UploadedFile $file, string $name): array
    {
        $sizes = config('media.sizes');
        $path = config('media.path');
        $quality = config('media.quality');
        $disk = config('media.disk');

        // Create image instance
        $image = Image::make($file);
        $width = $image->width();
        $height = $image->height();

        // Store original image
        Storage::disk($disk)->put(
            "$path/original/$name",
            (string) $image->encode(null, $quality)
        );

        // Generate WebP version if enabled
        if (config('media.auto_webp') && function_exists('imagewebp')) {
            $webpName = pathinfo($name, PATHINFO_FILENAME) . '.webp';
            Storage::disk($disk)->put(
                "$path/original/$webpName",
                (string) $image->encode('webp', $quality)
            );
        }

        // Generate resized versions
        foreach ($sizes as $folder => [$targetWidth, $targetHeight]) {
            $resized = Image::make($file)
                ->fit($targetWidth, $targetHeight, function ($constraint) {
                    $constraint->upsize(); // Don't upsize if image is smaller
                });

            Storage::disk($disk)->put(
                "$path/$folder/$name",
                (string) $resized->encode(null, $quality)
            );

            // Generate WebP version if enabled
            if (config('media.auto_webp') && function_exists('imagewebp')) {
                $webpName = pathinfo($name, PATHINFO_FILENAME) . '.webp';
                Storage::disk($disk)->put(
                    "$path/$folder/$webpName",
                    (string) $resized->encode('webp', $quality)
                );
            }
        }

        return compact('width', 'height');
    }

    /**
     * Generate unique filename.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = \Illuminate\Support\Str::random(8);
        
        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Validate image file.
     *
     * @param UploadedFile $file
     * @return bool
     * @throws \Exception
     */
    public function validateImage(UploadedFile $file): bool
    {
        $allowedMimes = config('media.allowed_mimes');
        $maxSize = config('media.max_size') * 1024; // Convert KB to bytes

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Invalid image type. Allowed types: ' . implode(', ', $allowedMimes));
        }

        if ($file->getSize() > $maxSize) {
            throw new \Exception('File size exceeds maximum allowed size of ' . config('media.max_size') . ' KB');
        }

        return true;
    }
}

