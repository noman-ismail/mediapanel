# MediaPanel - Standalone Laravel Package

## 🎯 Package Overview

MediaPanel is a **standalone Laravel package** for image uploading and management that can be integrated into **any Laravel Blade project**. It works independently and doesn't interfere with existing project code.

## ✅ Key Features

- ✅ **Standalone Package** - Works independently
- ✅ **Zero Conflicts** - Prefixed routes, namespaced code
- ✅ **Auto-Discovery** - No manual registration needed
- ✅ **Default Config** - Works without publishing config
- ✅ **Dependency Resolved** - All dependencies declared
- ✅ **Laravel 10+ Compatible** - Works with Laravel 10, 11, 12
- ✅ **Blade Integration** - Easy Blade component integration

## 📦 Installation

### Step 1: Install Package

```bash
composer require nomanismail/mediapanel
```

### Step 2: Publish Migrations

```bash
php artisan vendor:publish --tag=mediapanel-migrations
php artisan migrate
```

### Step 3: Publish Assets

```bash
php artisan vendor:publish --tag=mediapanel-assets
```

### Step 4: Create Storage Link

```bash
php artisan storage:link
```

### Step 5: (Optional) Publish Config

```bash
php artisan vendor:publish --tag=mediapanel-config
```

**Note:** Config is optional - package has defaults!

## 🔌 Integration in Any Blade Project

### Step 1: Add Assets to Layout

Add to your main layout file (`resources/views/layouts/app.blade.php`):

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Your App</title>
    
    {{-- MediaPanel CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">
</head>
<body>
    {{-- Your content --}}
    
    {{-- MediaPanel JavaScript --}}
    <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
</body>
</html>
```

### Step 2: Use in Forms

**Replace file input:**

```blade
{{-- Old way --}}
<input type="file" name="cover_image" id="cover_image">

{{-- New way --}}
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',
    'value' => old('cover_image'),
    'label' => 'Cover Image',
    'size' => 'medium'
])
```

### Step 3: Done!

MediaPanel is now integrated. Users can upload and manage images through the MediaPanel modal.

## 🛣️ Routes

Package routes are prefixed with `mediapanel` to avoid conflicts:

- `GET /mediapanel` - Media panel interface
- `POST /mediapanel` - Upload image
- `PUT /mediapanel/{id}` - Update media
- `DELETE /mediapanel/{id}` - Delete media
- `GET /mediapanel/search` - Search media
- `GET /mediapanel/folder` - Get by folder

**No conflicts with existing `/media` routes!**

## 📁 Package Structure

```
mediapanel/
├── config/
│   └── media.php              # Config (optional)
├── src/
│   ├── Database/
│   │   └── Migrations/        # Database migrations
│   ├── Http/
│   │   └── Controllers/       # Controllers
│   ├── Models/                # Eloquent models
│   ├── Repositories/          # Repository pattern
│   ├── Services/              # Business logic
│   ├── Traits/                # Reusable traits
│   ├── resources/
│   │   ├── assets/            # CSS & JS
│   │   └── views/             # Blade views
│   ├── routes/
│   │   └── web.php            # Routes (prefixed)
│   └── MediaPanelServiceProvider.php
└── composer.json              # Dependencies
```

## 🔒 Isolation Features

### 1. Namespaced Code
- All code under `NomanIsmail\MediaPanel` namespace
- No conflicts with project code

### 2. Prefixed Routes
- All routes under `/mediapanel` prefix
- Won't conflict with existing routes

### 3. Isolated Database
- Own tables: `media`, `media_folders`
- No conflicts with existing tables

### 4. Separate Assets
- Assets in `public/vendor/mediapanel/`
- No conflicts with project assets

### 5. Config Namespace
- Config key: `media`
- Won't conflict with other packages

## 📝 Usage Examples

### Example 1: Blog Post Form

```blade
{{-- resources/views/posts/create.blade.php --}}
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    
    <div class="mb-4">
        <label>Title</label>
        <input type="text" name="title" class="form-control">
    </div>
    
    {{-- MediaPanel Integration --}}
    <div class="mb-4">
        @include('mediapanel::components.media-input', [
            'name' => 'cover_image',
            'value' => old('cover_image'),
            'label' => 'Cover Image',
            'size' => 'large'
        ])
    </div>
    
    <button type="submit" class="btn btn-primary">Create Post</button>
</form>
```

### Example 2: Settings Page

```blade
{{-- resources/views/admin/settings.blade.php --}}
<form method="POST" action="{{ route('settings.update') }}">
    @csrf
    
    {{-- Logo --}}
    <div class="mb-4">
        @include('mediapanel::components.media-input', [
            'name' => 'logo',
            'value' => setting('logo'),
            'label' => 'Site Logo'
        ])
    </div>
    
    {{-- Favicon --}}
    <div class="mb-4">
        @include('mediapanel::components.media-input', [
            'name' => 'favicon',
            'value' => setting('favicon'),
            'label' => 'Favicon'
        ])
    </div>
    
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
```

### Example 3: With Rich Text Editor

```blade
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    
    <div class="mb-4">
        <label>Content</label>
        <textarea id="content" name="content"></textarea>
        
        {{-- Insert Image Button --}}
        @include('mediapanel::components.media-button', [
            'targetEditor' => '#content',
            'size' => 'medium',
            'label' => 'Insert Image'
        ])
    </div>
    
    <button type="submit">Submit</button>
</form>

<script src="https://cdn.tiny.cloud/1/YOUR_KEY/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 400
});
</script>
```

## 🔧 Configuration

### Default Configuration

Package works with defaults. No config needed!

**Default Values:**
- Disk: `public`
- Path: `media`
- Quality: `85`
- Max Size: `5120 KB` (5 MB)
- Sizes: `thumb` (150x150), `medium` (400x300), `large` (1200x800)

### Custom Configuration

Publish config and customize:

```bash
php artisan vendor:publish --tag=mediapanel-config
```

Edit `config/media.php`:

```php
return [
    'disk' => 'public',
    'path' => 'media',
    'sizes' => [
        'thumb' => [150, 150],
        'medium' => [400, 300],
        'large' => [1200, 800],
        'custom' => [800, 600], // Add custom size
    ],
    'quality' => 85,
    'max_size' => 5120, // KB
    'auto_webp' => false,
];
```

## ✅ Dependency Resolution

### All Dependencies Declared

```json
{
    "require": {
        "php": "^8.1",
        "laravel/framework": "^10.0|^11.0|^12.0",
        "intervention/image": "^2.7|^3.0"
    }
}
```

### Auto-Installed Dependencies

When you install MediaPanel, Composer automatically installs:
- Laravel Framework components
- Intervention Image
- All required dependencies

### No Manual Dependency Management Needed!

## 🚀 Quick Start

### Minimal Setup (3 Steps)

```bash
# 1. Install
composer require nomanismail/mediapanel

# 2. Migrate
php artisan vendor:publish --tag=mediapanel-migrations
php artisan migrate

# 3. Assets
php artisan vendor:publish --tag=mediapanel-assets
php artisan storage:link
```

### Add to Layout

```blade
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">
</head>
<body>
    {{-- Content --}}
    <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
</body>
```

### Use in Forms

```blade
@include('mediapanel::components.media-input', [
    'name' => 'image',
    'label' => 'Select Image'
])
```

**That's it! MediaPanel is ready to use!**

## 🎯 Integration Checklist

- [ ] Install package: `composer require nomanismail/mediapanel`
- [ ] Publish migrations: `php artisan vendor:publish --tag=mediapanel-migrations`
- [ ] Run migrations: `php artisan migrate`
- [ ] Publish assets: `php artisan vendor:publish --tag=mediapanel-assets`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Add CSS to layout: `<link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">`
- [ ] Add JS to layout: `<script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>`
- [ ] Add CSRF token: `<meta name="csrf-token" content="{{ csrf_token() }}">`
- [ ] Use components in forms: `@include('mediapanel::components.media-input')`

## 🔍 Verification

### Check Package Discovery

```bash
php artisan package:discover
```

Should show: `Discovered Package: nomanismail/mediapanel`

### Check Routes

```bash
php artisan route:list | grep mediapanel
```

Should show routes prefixed with `mediapanel.`

### Test Installation

1. Visit: `http://your-domain/mediapanel`
2. Should see MediaPanel interface
3. Try uploading an image

## 🛡️ Conflict Prevention

### Route Conflicts
- ✅ Routes prefixed: `/mediapanel/*`
- ✅ Won't conflict with `/media` routes

### Database Conflicts
- ✅ Tables: `media`, `media_folders`
- ✅ Checked before creation

### Asset Conflicts
- ✅ Assets in: `public/vendor/mediapanel/`
- ✅ Separate from project assets

### Config Conflicts
- ✅ Config key: `media`
- ✅ Namespaced

## 📚 Documentation

- [README.md](README.md) - Complete documentation
- [INTEGRATION_GUIDE.md](INTEGRATION_GUIDE.md) - Integration examples
- [QUICK_START.md](QUICK_START.md) - Quick start guide
- [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) - Installation steps

## 🎉 Result

**MediaPanel is now:**
- ✅ Standalone Laravel package
- ✅ Works in any Blade project
- ✅ Zero conflicts
- ✅ All dependencies resolved
- ✅ Production ready
- ✅ Easy to integrate

**Ready for any Laravel project!** 🚀

