# Testing the Package

## Quick Test Checklist

### 1. Install Package Locally

```bash
# In your Laravel project
composer require nomanismail/mediapanel:dev-main
# Or if using local path:
composer config repositories.mediapanel path ../mediapanel
composer require nomanismail/mediapanel:@dev
```

### 2. Verify Service Provider Registration

```bash
php artisan package:discover
```

Check output for: `Discovered Package: nomanismail/mediapanel`

### 3. Publish Configuration

```bash
php artisan vendor:publish --tag=mediapanel-config
```

Verify file exists: `config/media.php`

### 4. Publish Migrations

```bash
php artisan vendor:publish --tag=mediapanel-migrations
php artisan migrate
```

Verify tables created:
- `media`
- `media_folders`

### 5. Create Storage Link

```bash
php artisan storage:link
```

Verify link exists: `public/storage` → `storage/app/public`

### 6. Test Routes

Visit: `http://your-app.test/media`

Should see the media panel interface.

### 7. Test Image Upload

1. Upload an image via the form
2. Check `storage/app/public/media/` for:
   - `original/` folder with original image
   - `thumb/` folder with 150x150 image
   - `medium/` folder with 400x300 image
   - `large/` folder with 1200x800 image

### 8. Test API Endpoints

```bash
# Upload via API
curl -X POST http://your-app.test/media \
  -F "image=@/path/to/image.jpg" \
  -F "title=Test Image"

# Search
curl http://your-app.test/media/search?query=test

# Get by folder
curl http://your-app.test/media/folder?folder_id=1
```

### 9. Test Model Usage

```php
use NomanIsmail\MediaPanel\Models\Media;

// Get media
$media = Media::first();

// Get URLs
$originalUrl = $media->original_url;
$thumbUrl = $media->getUrl('thumb');
$mediumUrl = $media->getUrl('medium');
$largeUrl = $media->getUrl('large');
```

### 10. Test Service Usage

```php
use NomanIsmail\MediaPanel\Services\MediaService;
use Illuminate\Http\Request;

// In controller
public function upload(Request $request, MediaService $mediaService)
{
    $result = $mediaService->upload(
        $request->file('image'),
        ['title' => 'My Image']
    );
    
    if ($result['success']) {
        return response()->json($result);
    }
    
    return response()->json($result, 400);
}
```

## Common Issues & Solutions

### Issue: Service Provider Not Found

**Solution:**
```bash
composer dump-autoload
php artisan package:discover
```

### Issue: Routes Not Loading

**Solution:**
- Check `routes/web.php` doesn't override package routes
- Clear route cache: `php artisan route:clear`

### Issue: Views Not Found

**Solution:**
```bash
php artisan view:clear
php artisan vendor:publish --tag=mediapanel-views
```

### Issue: Images Not Displaying

**Solution:**
1. Verify storage link: `php artisan storage:link`
2. Check permissions: `chmod -R 775 storage`
3. Verify disk config: `config/filesystems.php` → `'default' => 'public'`

### Issue: Intervention Image Not Working

**Solution:**
```bash
# Install GD extension
sudo apt-get install php-gd

# Or Imagick
sudo apt-get install php-imagick

# Restart PHP-FPM
sudo service php8.3-fpm restart
```

## Manual Testing Script

Create `test-mediapanel.php`:

```php
<?php

require __DIR__.'/vendor/autoload.php';

use NomanIsmail\MediaPanel\Services\MediaService;
use NomanIsmail\MediaPanel\Repositories\MediaRepository;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test repository
$repository = new MediaRepository();
$media = $repository->all();
echo "Media count: " . $media->count() . "\n";

// Test service
$service = new MediaService($repository);
$result = $service->getAll();
echo "Service result: " . ($result['success'] ? 'Success' : 'Failed') . "\n";
```

Run: `php test-mediapanel.php`

