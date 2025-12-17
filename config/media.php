<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Media Storage Disk
    |--------------------------------------------------------------------------
    |
    | The disk where media files will be stored. Default is 'public'.
    | Make sure to run: php artisan storage:link
    |
    */
    'disk' => env('MEDIA_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Image Sizes Configuration
    |--------------------------------------------------------------------------
    |
    | Define the sizes for image resizing. Format: [width, height]
    | Images will be resized maintaining aspect ratio.
    |
    */
    'sizes' => [
        'thumb' => [150, 150],
        'medium' => [400, 300],
        'large' => [1200, 800],
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Storage Path
    |--------------------------------------------------------------------------
    |
    | Base path for storing media files within the storage disk.
    | Files will be stored in: storage/app/public/{path}/
    |
    */
    'path' => env('MEDIA_PATH', 'media'),

    /*
    |--------------------------------------------------------------------------
    | Image Quality
    |--------------------------------------------------------------------------
    |
    | JPEG quality for resized images (1-100).
    | Higher quality = larger file size.
    |
    */
    'quality' => env('MEDIA_QUALITY', 85),

    /*
    |--------------------------------------------------------------------------
    | Maximum File Size
    |--------------------------------------------------------------------------
    |
    | Maximum file size in kilobytes (KB).
    | Default: 5120 KB (5 MB)
    |
    */
    'max_size' => env('MEDIA_MAX_SIZE', 5120),

    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types
    |--------------------------------------------------------------------------
    |
    | Allowed image MIME types for upload.
    |
    */
    'allowed_mimes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto WebP Conversion
    |--------------------------------------------------------------------------
    |
    | Automatically create WebP versions of uploaded images.
    | Requires GD or Imagick with WebP support.
    |
    */
    'auto_webp' => env('MEDIA_AUTO_WEBP', false),

    /*
    |--------------------------------------------------------------------------
    | Folder Organization
    |--------------------------------------------------------------------------
    |
    | Enable folder-based organization for media files.
    |
    */
    'folders_enabled' => env('MEDIA_FOLDERS_ENABLED', true),
];

