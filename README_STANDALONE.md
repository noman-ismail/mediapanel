# MediaPanel - Standalone Laravel Package

## 🎯 What is MediaPanel?

MediaPanel is a **standalone Laravel package** for image uploading and management that can be integrated into **any Laravel Blade project**. It's completely independent and won't interfere with your existing code.

## ✨ Key Features

- ✅ **Standalone Package** - Works independently in any Laravel project
- ✅ **Zero Conflicts** - Prefixed routes (`/mediapanel/*`), namespaced code
- ✅ **Auto-Discovery** - No manual service provider registration needed
- ✅ **Default Config** - Works without publishing config (has defaults)
- ✅ **All Dependencies Resolved** - Composer handles everything
- ✅ **Laravel 10+ Compatible** - Works with Laravel 10, 11, 12
- ✅ **Blade Components** - Easy integration with Blade templates
- ✅ **Production Ready** - Error handling, logging, validation

## 📦 Installation

### Quick Install (5 Steps)

```bash
# 1. Install package
composer require nomanismail/mediapanel

# 2. Publish migrations
php artisan vendor:publish --tag=mediapanel-migrations

# 3. Run migrations
php artisan migrate

# 4. Publish assets
php artisan vendor:publish --tag=mediapanel-assets

# 5. Create storage link
php artisan storage:link
```

**That's it!** Package is ready to use.

## 🔌 Integration in Your Blade Project

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

### Step 3: Done! ✅

MediaPanel is now integrated. When users click the button, MediaPanel modal opens instead of browser file picker.

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
            'size' => 'large',
            'required' => true
        ])
    </div>
    
    <button type="submit" class="btn btn-primary">Create Post</button>
</form>
```

### Example 2: Settings Page

```blade
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
    
    <button type="submit">Save Settings</button>
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

## 🛣️ Routes

All routes are prefixed with `/mediapanel` to avoid conflicts:

- `GET /mediapanel` - Media panel interface
- `POST /mediapanel` - Upload image
- `PUT /mediapanel/{id}` - Update media
- `DELETE /mediapanel/{id}` - Delete media
- `GET /mediapanel/search` - Search media
- `GET /mediapanel/folder` - Get by folder

**No conflicts with existing `/media` routes!**

## ⚙️ Configuration

### Default Configuration

Package works with defaults. No config needed!

**Default Values:**
- Disk: `public`
- Path: `media`
- Quality: `85`
- Max Size: `5120 KB` (5 MB)
- Sizes: `thumb` (150x150), `medium` (400x300), `large` (1200x800)

### Custom Configuration (Optional)

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

**Composer automatically installs all dependencies!**

### No Manual Dependency Management Needed!

## 🔒 Conflict Prevention

### 1. Routes
- ✅ Prefixed: `/mediapanel/*`
- ✅ Won't conflict with `/media` routes

### 2. Database
- ✅ Tables: `media`, `media_folders`
- ✅ Checked before creation

### 3. Assets
- ✅ Path: `public/vendor/mediapanel/`
- ✅ Separate from project assets

### 4. Config
- ✅ Key: `media`
- ✅ Namespaced

### 5. Code
- ✅ Namespace: `NomanIsmail\MediaPanel`
- ✅ No conflicts with project code

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

**That's it! MediaPanel is ready!**

## 📚 Documentation

- [README.md](README.md) - Complete documentation
- [INTEGRATION_GUIDE.md](INTEGRATION_GUIDE.md) - Integration examples
- [QUICK_START.md](QUICK_START.md) - Quick start guide
- [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) - Installation steps
- [STANDALONE_PACKAGE.md](STANDALONE_PACKAGE.md) - Standalone package guide

## 🎉 Result

**MediaPanel is now:**
- ✅ Standalone Laravel package
- ✅ Works in any Blade project
- ✅ Zero conflicts
- ✅ All dependencies resolved
- ✅ Production ready
- ✅ Easy to integrate

**Ready for any Laravel project!** 🚀

