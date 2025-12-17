# MediaPanel - Standalone Laravel Package Guide

## 🎯 What is MediaPanel?

MediaPanel is a **standalone Laravel package** for image uploading and management. It can be integrated into **any Laravel Blade project** without interfering with your existing code.

## ✅ Package Status: Production Ready

### Standalone Features
- ✅ **Independent Package** - Works separately from your project
- ✅ **No Conflicts** - Prefixed routes, namespaced code, isolated database
- ✅ **Auto-Discovery** - No manual registration needed
- ✅ **Default Config** - Works without publishing config
- ✅ **All Dependencies Resolved** - Composer handles everything

## 📦 Installation in Any Blade Project

### Step 1: Install Package

```bash
composer require nomanismail/mediapanel
```

**What happens:**
- Package downloads via Composer
- Service provider auto-discovers
- All dependencies auto-installed
- No manual configuration needed

### Step 2: Setup Database

```bash
php artisan vendor:publish --tag=mediapanel-migrations
php artisan migrate
```

**Creates tables:**
- `media_folders` - For organizing media
- `media` - For storing media records

### Step 3: Setup Assets

```bash
php artisan vendor:publish --tag=mediapanel-assets
php artisan storage:link
```

**Copies files:**
- `public/vendor/mediapanel/mediapanel.css`
- `public/vendor/mediapanel/mediapanel.js`

### Step 4: (Optional) Customize Config

```bash
php artisan vendor:publish --tag=mediapanel-config
```

**Note:** Config is optional - package has defaults!

## 🔌 Integration Steps

### Step 1: Add Assets to Your Layout

**File:** `resources/views/layouts/app.blade.php` (or your main layout)

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- REQUIRED: CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Your Application</title>
    
    {{-- Your existing CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    {{-- MediaPanel CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">
</head>
<body>
    @yield('content')
    
    {{-- Your existing JavaScript --}}
    <script src="{{ asset('js/app.js') }}"></script>
    
    {{-- MediaPanel JavaScript (before </body>) --}}
    <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
</body>
</html>
```

### Step 2: Replace File Inputs

**Before:**
```blade
<input type="file" name="cover_image" id="cover_image" accept="image/*">
```

**After:**
```blade
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',
    'value' => old('cover_image'),
    'label' => 'Cover Image',
    'size' => 'medium'
])
```

### Step 3: Done! ✅

MediaPanel is now integrated. When users click the button, MediaPanel modal opens.

## 📝 Complete Example

```blade
{{-- resources/views/posts/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Post</h1>
    
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        
        {{-- Title --}}
        <div class="mb-4">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        
        {{-- Cover Image with MediaPanel --}}
        <div class="mb-4">
            @include('mediapanel::components.media-input', [
                'name' => 'cover_image',
                'value' => old('cover_image'),
                'label' => 'Cover Image',
                'size' => 'large',
                'required' => true
            ])
        </div>
        
        {{-- Content --}}
        <div class="mb-4">
            <label>Content</label>
            <textarea name="content" class="form-control" rows="10"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
</div>
@endsection
```

## 🛣️ Routes (No Conflicts)

All routes are prefixed with `/mediapanel`:

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/mediapanel` | Media panel interface |
| POST | `/mediapanel` | Upload image |
| PUT | `/mediapanel/{id}` | Update media |
| DELETE | `/mediapanel/{id}` | Delete media |
| GET | `/mediapanel/search` | Search media |
| GET | `/mediapanel/folder` | Get by folder |

**Won't conflict with existing `/media` routes!**

## 🔒 Conflict Prevention

### Routes
- ✅ MediaPanel: `/mediapanel/*`
- ✅ Your Project: `/media/*` (or any other routes)
- ✅ **No conflicts!**

### Database
- ✅ MediaPanel: `media`, `media_folders` tables
- ✅ Your Project: Your existing tables
- ✅ **No conflicts!**

### Code
- ✅ MediaPanel: `NomanIsmail\MediaPanel` namespace
- ✅ Your Project: `App\*` namespace
- ✅ **No conflicts!**

### Assets
- ✅ MediaPanel: `public/vendor/mediapanel/`
- ✅ Your Project: `public/css/`, `public/js/`
- ✅ **No conflicts!**

### Config
- ✅ MediaPanel: `config/media.php`
- ✅ Your Project: Your config files
- ✅ **No conflicts!**

## 📚 Dependencies

### All Declared in composer.json

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

## ✅ Verification

After installation, verify:

```bash
# 1. Check package discovery
php artisan package:discover
# Should show: Discovered Package: nomanismail/mediapanel

# 2. Check routes
php artisan route:list | grep mediapanel
# Should show routes prefixed with mediapanel.

# 3. Check tables
php artisan migrate:status
# Should show media and media_folders migrations

# 4. Visit MediaPanel
# Go to: http://your-domain/mediapanel
# Should see MediaPanel interface
```

## 🎨 Component Usage

### media-input Component

```blade
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',        // Required: Input field name
    'value' => '',                  // Optional: Current image URL
    'label' => 'Cover Image',       // Optional: Label text
    'size' => 'medium',             // Optional: thumb, medium, large, original
    'required' => false,            // Optional: Required field
    'preview' => true              // Optional: Show preview
])
```

### media-button Component

```blade
@include('mediapanel::components.media-button', [
    'targetInput' => '#input_id',   // Optional: Input selector
    'targetEditor' => '#editor_id', // Optional: Editor selector
    'size' => 'original',           // Optional: Image size
    'multiple' => false,            // Optional: Multiple selection
    'label' => 'Select Image',      // Optional: Button text
    'class' => 'btn btn-primary'    // Optional: CSS classes
])
```

## 🚀 Quick Start Summary

### Installation (3 Commands)

```bash
composer require nomanismail/mediapanel
php artisan vendor:publish --tag=mediapanel-migrations && php artisan migrate
php artisan vendor:publish --tag=mediapanel-assets && php artisan storage:link
```

### Integration (3 Steps)

1. **Add to layout:**
   ```blade
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">
   <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
   ```

2. **Use component:**
   ```blade
   @include('mediapanel::components.media-input', ['name' => 'image'])
   ```

3. **Done!** ✅

## 📖 Documentation Files

- [README.md](README.md) - Complete documentation
- [INTEGRATION_FOR_ANY_BLADE_PROJECT.md](INTEGRATION_FOR_ANY_BLADE_PROJECT.md) - Integration guide
- [BLADE_INTEGRATION_EXAMPLES.md](BLADE_INTEGRATION_EXAMPLES.md) - Real-world examples
- [QUICK_START.md](QUICK_START.md) - Quick start guide
- [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) - Installation steps

## 🎉 Final Result

**MediaPanel is now:**
- ✅ **Standalone Laravel package**
- ✅ **Works in any Blade project**
- ✅ **Zero conflicts** (prefixed routes, namespaced code)
- ✅ **All dependencies resolved** (Composer handles everything)
- ✅ **Production ready** (error handling, validation, logging)
- ✅ **Easy to integrate** (3 steps)

**Ready to be installed in any Laravel Blade project!** 🚀

