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
        $sizes = config('media.sizes', [
            'thumb' => [150, 150],
            'medium' => [400, 300],
            'large' => [1200, 800],
        ]);
        $path = config('media.path', 'media');
        $quality = config('media.quality', 85);
        $disk = config('media.disk', 'public');
        $autoWebp = config('media.auto_webp', false);

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
        if ($autoWebp && $this->supportsWebP()) {
            try {
                $webpName = pathinfo($name, PATHINFO_FILENAME) . '.webp';
                Storage::disk($disk)->put(
                    "$path/original/$webpName",
                    (string) $image->encode('webp', $quality)
                );
            } catch (\Exception $e) {
                // WebP encoding failed, continue without WebP
            }
        }

        // Generate resized versions
        foreach ($sizes as $folder => $dimensions) {
            if (!is_array($dimensions) || count($dimensions) < 2) {
                continue; // Skip invalid size definitions
            }

            [$targetWidth, $targetHeight] = $dimensions;

            try {
                $resized = Image::make($file);
                
                // Use fit() method which works in both v2 and v3
                if (method_exists($resized, 'fit')) {
                    $resized->fit($targetWidth, $targetHeight, function ($constraint) {
                        if (method_exists($constraint, 'upsize')) {
                            $constraint->upsize(); // Don't upsize if image is smaller
                        }
                    });
                } else {
                    // Fallback for older versions
                    $resized->resize($targetWidth, $targetHeight, function ($constraint) {
                        $constraint->aspectRatio();
                        if (method_exists($constraint, 'upsize')) {
                            $constraint->upsize();
                        }
                    });
                }

                Storage::disk($disk)->put(
                    "$path/$folder/$name",
                    (string) $resized->encode(null, $quality)
                );

                // Generate WebP version if enabled
                if ($autoWebp && $this->supportsWebP()) {
                    try {
                        $webpName = pathinfo($name, PATHINFO_FILENAME) . '.webp';
                        Storage::disk($disk)->put(
                            "$path/$folder/$webpName",
                            (string) $resized->encode('webp', $quality)
                        );
                    } catch (\Exception $e) {
                        // WebP encoding failed, continue without WebP
                    }
                }
            } catch (\Exception $e) {
                // Skip this size if processing fails
                continue;
            }
        }

        return compact('width', 'height');
    }

    /**
     * Check if WebP is supported.
     *
     * @return bool
     */
    protected function supportsWebP(): bool
    {
        if (!function_exists('imagewebp')) {
            return false;
        }

        // Check GD extension
        if (extension_loaded('gd')) {
            $info = gd_info();
            return isset($info['WebP Support']) && $info['WebP Support'];
        }

        // Check Imagick extension
        if (extension_loaded('imagick')) {
            $imagick = new \Imagick();
            $formats = $imagick->queryFormats();
            return in_array('WEBP', $formats);
        }

        return false;
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
        $allowedMimes = config('media.allowed_mimes', [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ]);
        $maxSize = (config('media.max_size', 5120)) * 1024; // Convert KB to bytes

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Invalid image type. Allowed types: ' . implode(', ', $allowedMimes));
        }

        if ($file->getSize() > $maxSize) {
            $maxSizeKB = config('media.max_size', 5120);
            throw new \Exception("File size exceeds maximum allowed size of {$maxSizeKB} KB");
        }

        return true;
    }
}

