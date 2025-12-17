# MediaPanel Installation Guide

Complete installation guide for the MediaPanel Laravel package.

## Requirements

- PHP >= 8.1
- Laravel >= 10.0
- Intervention Image >= 2.7 or >= 3.0
- GD or Imagick extension (for image processing)
- Composer

## Installation

### Step 1: Install via Composer

```bash
composer require nomanismail/mediapanel
```

**Or for development:**

```bash
composer require nomanismail/mediapanel:@dev
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --tag=mediapanel-config
```

This creates `config/media.php` in your project.

### Step 3: Publish Migrations

```bash
php artisan vendor:publish --tag=mediapanel-migrations
php artisan migrate
```

This creates the `media` and `media_folders` tables.

### Step 4: Publish Assets

```bash
php artisan vendor:publish --tag=mediapanel-assets
```

This copies CSS and JavaScript files to `public/vendor/mediapanel/`.

### Step 5: Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`.

### Step 6: Configure Environment (Optional)

Add to your `.env` file:

```env
MEDIA_DISK=public
MEDIA_PATH=media
MEDIA_QUALITY=85
MEDIA_MAX_SIZE=5120
MEDIA_AUTO_WEBP=false
MEDIA_FOLDERS_ENABLED=true
```

## Verification

### Check Package Discovery

```bash
php artisan package:discover
```

You should see: `Discovered Package: nomanismail/mediapanel`

### Check Routes

```bash
php artisan route:list | grep mediapanel
```

You should see routes like:
- `GET /media` - Media panel interface
- `POST /media` - Upload image
- `PUT /media/{id}` - Update media
- `DELETE /media/{id}` - Delete media

### Test Installation

1. Visit `/media` in your browser
2. Should see MediaPanel interface
3. Try uploading an image

## Troubleshooting

### Package Not Discovered

```bash
php artisan config:clear
php artisan cache:clear
php artisan package:discover
```

### Assets Not Loading

1. Check `public/vendor/mediapanel/` exists
2. Run: `php artisan vendor:publish --tag=mediapanel-assets --force`
3. Clear cache: `php artisan cache:clear`

### Storage Link Issues

```bash
# Remove existing link
rm public/storage

# Create new link
php artisan storage:link
```

### Migration Errors

```bash
# Check if tables exist
php artisan migrate:status

# Rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### Intervention Image Not Found

```bash
composer require intervention/image
```

### Permission Issues

```bash
# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## Next Steps

After installation, see:
- [Integration Guide](INTEGRATION_GUIDE.md) - How to use in your project
- [Quick Start](QUICK_START.md) - Quick integration examples
- [README.md](README.md) - Complete documentation

## Support

For issues, visit: https://github.com/noman-ismail/mediapanel/issues

