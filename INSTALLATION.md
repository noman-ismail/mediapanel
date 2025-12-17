# Installation Guide

## Step 1: Install via Composer

```bash
composer require nomanismail/mediapanel
```

## Step 2: Publish Configuration

```bash
php artisan vendor:publish --tag=mediapanel-config
```

This will copy `config/media.php` to your project's config directory.

## Step 3: Publish Migrations

```bash
php artisan vendor:publish --tag=mediapanel-migrations
php artisan migrate
```

This will create the `media` and `media_folders` tables.

## Step 4: Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `storage/app/public` to `public/storage`.

## Step 5: Configure Tailwind CSS (if not already configured)

Add to your `tailwind.config.js`:

```js
content: [
    "./resources/**/*.blade.php",
    "./vendor/nomanismail/mediapanel/**/*.blade.php",
],
```

## Step 6: Access Media Panel

Visit `/media` in your browser to access the media panel.

## Optional: Customize Views

If you want to customize the views:

```bash
php artisan vendor:publish --tag=mediapanel-views
```

This will copy views to `resources/views/vendor/mediapanel/`.

## Troubleshooting

### Storage Link Issues

If images are not displaying, ensure:
1. Storage link is created: `php artisan storage:link`
2. Storage disk is set to 'public' in `config/filesystems.php`
3. Permissions are correct: `chmod -R 775 storage`

### Intervention Image Issues

Ensure GD or Imagick extension is installed:

```bash
# Check if GD is installed
php -m | grep gd

# Or check Imagick
php -m | grep imagick
```

### Route Not Found

If routes are not loading, ensure the service provider is registered. Check `config/app.php` or run:

```bash
php artisan package:discover
```

