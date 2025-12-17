# MediaPanel - Integration Guide for Any Blade Project

## 🎯 Overview

MediaPanel is a **standalone Laravel package** designed to be integrated into **any Laravel Blade project** for image uploading and management. It's completely independent and won't interfere with your existing code.

## ✅ Package Characteristics

### Standalone & Independent
- ✅ **Separate Namespace**: `NomanIsmail\MediaPanel`
- ✅ **Prefixed Routes**: `/mediapanel/*` (no conflicts)
- ✅ **Isolated Database**: Own tables (`media`, `media_folders`)
- ✅ **Separate Assets**: `public/vendor/mediapanel/`
- ✅ **Config Namespace**: `media` (won't conflict)

### Works Out of the Box
- ✅ **Auto-Discovery**: No manual service provider registration
- ✅ **Default Config**: Works without publishing config
- ✅ **All Dependencies**: Declared in `composer.json`
- ✅ **Zero Conflicts**: Prefixed routes, namespaced code

## 📦 Installation in Any Blade Project

### Step 1: Install Package

```bash
composer require nomanismail/mediapanel
```

**What happens:**
- Composer downloads package
- Auto-discovers service provider
- Resolves all dependencies automatically
- No manual configuration needed

### Step 2: Publish Migrations

```bash
php artisan vendor:publish --tag=mediapanel-migrations
php artisan migrate
```

**Creates tables:**
- `media_folders` (for organization)
- `media` (for file records)

### Step 3: Publish Assets

```bash
php artisan vendor:publish --tag=mediapanel-assets
php artisan storage:link
```

**Copies files:**
- `public/vendor/mediapanel/mediapanel.css`
- `public/vendor/mediapanel/mediapanel.js`

### Step 4: (Optional) Publish Config

```bash
php artisan vendor:publish --tag=mediapanel-config
```

**Note:** Config is optional - package has defaults!

## 🔌 Integration Steps

### Step 1: Add Assets to Your Layout

**File:** `resources/views/layouts/app.blade.php` (or your main layout)

```blade
<!DOCTYPE html>
<html lang="en">
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
    {{-- Your content --}}
    @yield('content')
    
    {{-- Your existing JavaScript --}}
    <script src="{{ asset('js/app.js') }}"></script>
    
    {{-- MediaPanel JavaScript (before closing body tag) --}}
    <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
</body>
</html>
```

### Step 2: Replace File Inputs with MediaPanel

**Before (Standard File Input):**
```blade
<input type="file" name="cover_image" id="cover_image" accept="image/*">
```

**After (MediaPanel Component):**
```blade
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',
    'value' => old('cover_image', $post->cover_image ?? ''),
    'label' => 'Cover Image',
    'size' => 'medium'
])
```

**That's it!** MediaPanel modal will open instead of browser file picker.

## 📝 Complete Integration Examples

### Example 1: Blog Post Creation Form

```blade
{{-- resources/views/posts/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Post</h1>
    
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        
        {{-- Title --}}
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium mb-2">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
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
            <label for="content" class="block text-sm font-medium mb-2">Content</label>
            <textarea name="content" id="content" class="form-control" rows="10">{{ old('content') }}</textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
</div>
@endsection
```

### Example 2: Settings Page

```blade
{{-- resources/views/admin/settings.blade.php --}}
@extends('layouts.admin')

@section('content')
<form method="POST" action="{{ route('settings.update') }}">
    @csrf
    @method('PUT')
    
    <h2>Site Settings</h2>
    
    {{-- Site Logo --}}
    <div class="mb-6">
        <label class="block text-sm font-medium mb-2">Site Logo</label>
        @include('mediapanel::components.media-input', [
            'name' => 'site_logo',
            'value' => setting('site_logo'),
            'label' => 'Upload Logo',
            'size' => 'medium'
        ])
    </div>
    
    {{-- Favicon --}}
    <div class="mb-6">
        <label class="block text-sm font-medium mb-2">Favicon</label>
        @include('mediapanel::components.media-input', [
            'name' => 'favicon',
            'value' => setting('favicon'),
            'label' => 'Upload Favicon',
            'size' => 'thumb'
        ])
    </div>
    
    {{-- Hero Image --}}
    <div class="mb-6">
        <label class="block text-sm font-medium mb-2">Hero Image</label>
        @include('mediapanel::components.media-input', [
            'name' => 'hero_image',
            'value' => setting('hero_image'),
            'label' => 'Upload Hero Image',
            'size' => 'large'
        ])
    </div>
    
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
@endsection
```

### Example 3: Rich Text Editor Integration

```blade
{{-- resources/views/posts/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('posts.update', $post->id) }}">
    @csrf
    @method('PUT')
    
    <div class="mb-4">
        <label>Content</label>
        <textarea id="content" name="content">{{ old('content', $post->content) }}</textarea>
        
        {{-- Insert Image Button --}}
        <div class="mt-2">
            @include('mediapanel::components.media-button', [
                'targetEditor' => '#content',
                'size' => 'medium',
                'label' => 'Insert Image',
                'class' => 'btn btn-secondary'
            ])
        </div>
    </div>
    
    <button type="submit">Update Post</button>
</form>

{{-- TinyMCE --}}
<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 400,
    plugins: 'image link lists',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image'
});
</script>
@endsection
```

### Example 4: Multiple Image Selection

```blade
{{-- Gallery Selection --}}
<form method="POST" action="{{ route('galleries.store') }}">
    @csrf
    
    <div class="mb-4">
        <label>Gallery Images</label>
        <input type="hidden" name="gallery_images" id="gallery_images" value="{{ old('gallery_images') }}">
        
        @include('mediapanel::components.media-button', [
            'targetInput' => '#gallery_images',
            'multiple' => true,
            'size' => 'medium',
            'label' => 'Select Multiple Images',
            'class' => 'btn btn-primary'
        ])
        
        {{-- Preview Selected Images --}}
        <div id="gallery-preview" class="mt-4 grid grid-cols-4 gap-4">
            @if(old('gallery_images'))
                @foreach(explode(',', old('gallery_images')) as $url)
                    @if($url)
                        <img src="{{ $url }}" alt="Preview" class="w-full h-32 object-cover rounded">
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    
    <button type="submit">Create Gallery</button>
</form>

<script>
document.getElementById('gallery_images').addEventListener('change', function() {
    const urls = this.value.split(',').filter(Boolean);
    const preview = document.getElementById('gallery-preview');
    preview.innerHTML = urls.map(url => 
        `<img src="${url}" alt="Preview" class="w-full h-32 object-cover rounded">`
    ).join('');
});
</script>
```

## 🛣️ Routes Available

After installation, these routes are available:

```
GET    /mediapanel          - Media panel interface
POST   /mediapanel          - Upload image
PUT    /mediapanel/{id}     - Update media metadata
DELETE /mediapanel/{id}     - Delete media
GET    /mediapanel/search   - Search media
GET    /mediapanel/folder   - Get media by folder
```

**All routes are prefixed with `/mediapanel` to avoid conflicts!**

## 🎨 Component Options

### `media-input` Component

```blade
@include('mediapanel::components.media-input', [
    'name' => 'image',              // Required: Input field name
    'value' => '',                  // Optional: Current image URL
    'label' => 'Select Image',      // Optional: Label text
    'size' => 'original',           // Optional: thumb, medium, large, original
    'required' => false,            // Optional: Required field
    'preview' => true              // Optional: Show preview
])
```

### `media-button` Component

```blade
@include('mediapanel::components.media-button', [
    'targetInput' => '#input_id',   // Optional: Input selector
    'targetEditor' => '#editor_id',  // Optional: Editor selector
    'size' => 'original',            // Optional: Image size
    'multiple' => false,             // Optional: Multiple selection
    'label' => 'Select Image',       // Optional: Button text
    'class' => 'btn btn-primary'     // Optional: CSS classes
])
```

## 🔧 Manual Integration (Without Components)

If you prefer manual integration:

```blade
{{-- Hidden input --}}
<input type="hidden" name="cover_image" id="cover_image" value="{{ old('cover_image') }}">

{{-- Trigger button --}}
<button 
    type="button"
    class="btn btn-primary"
    data-mediapanel
    data-target-input="#cover_image"
    data-size="medium">
    Select Image
</button>

{{-- Preview --}}
<div id="preview" class="mt-4">
    @if(old('cover_image'))
        <img src="{{ old('cover_image') }}" alt="Preview" class="w-32 h-32 object-cover rounded">
    @endif
</div>

<script>
document.getElementById('cover_image').addEventListener('change', function() {
    const preview = document.getElementById('preview');
    if (this.value) {
        preview.innerHTML = `<img src="${this.value}" alt="Preview" class="w-32 h-32 object-cover rounded">`;
    }
});
</script>
```

## ✅ Verification Checklist

After installation, verify:

- [ ] Package discovered: `php artisan package:discover`
- [ ] Routes registered: `php artisan route:list | grep mediapanel`
- [ ] Tables created: Check database for `media` and `media_folders`
- [ ] Assets accessible: Visit `/vendor/mediapanel/mediapanel.css` in browser
- [ ] MediaPanel opens: Visit `/mediapanel` in browser
- [ ] Upload works: Try uploading an image
- [ ] Component works: Use component in a form

## 🚨 Troubleshooting

### Package Not Discovered
```bash
php artisan config:clear
php artisan cache:clear
php artisan package:discover
```

### Routes Not Working
```bash
php artisan route:clear
php artisan route:list | grep mediapanel
```

### Assets Not Loading
```bash
php artisan vendor:publish --tag=mediapanel-assets --force
php artisan cache:clear
```

### Storage Link Issues
```bash
rm public/storage
php artisan storage:link
```

### Migration Errors
```bash
php artisan migrate:status
php artisan migrate:rollback
php artisan migrate
```

## 📚 Additional Resources

- [README.md](README.md) - Complete package documentation
- [QUICK_START.md](QUICK_START.md) - Quick start guide
- [INTEGRATION_GUIDE.md](INTEGRATION_GUIDE.md) - Detailed integration examples
- [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md) - Installation steps

## 🎉 Summary

MediaPanel is a **standalone Laravel package** that:

1. ✅ **Installs easily**: `composer require nomanismail/mediapanel`
2. ✅ **Works independently**: No conflicts with existing code
3. ✅ **Integrates simply**: Just add assets and use components
4. ✅ **Resolves dependencies**: Composer handles everything
5. ✅ **Works out of the box**: Defaults for all config

**Ready to integrate into any Blade project!** 🚀

