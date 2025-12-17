# MediaPanel Integration Guide

Complete guide for integrating MediaPanel into your Laravel Blade templates and replacing file inputs.

## Quick Start

### 1. Include Assets

Add to your main layout (`resources/views/layouts/app.blade.php`):

```blade
<head>
    {{-- CSRF Token (REQUIRED) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- MediaPanel CSS --}}
    <link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">
</head>

<body>
    {{-- Your content --}}
    
    {{-- MediaPanel JavaScript (before </body>) --}}
    <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
</body>
```

**Or publish assets:**

```bash
php artisan vendor:publish --tag=mediapanel-assets
```

Then use:
```blade
<link rel="stylesheet" href="{{ asset('css/mediapanel.css') }}">
<script src="{{ asset('js/mediapanel.js') }}"></script>
```

### 2. Replace File Input

#### Simple Replacement

**Before:**
```blade
<input type="file" name="cover_image" id="cover_image">
```

**After:**
```blade
{{-- Hidden input for form submission --}}
<input type="hidden" name="cover_image" id="cover_image" value="{{ old('cover_image') }}">

{{-- MediaPanel Button --}}
<button 
    type="button"
    class="px-4 py-2 bg-indigo-600 text-white rounded-lg"
    data-mediapanel
    data-target-input="#cover_image"
    data-size="medium">
    Select Image
</button>

{{-- Preview (optional) --}}
<div id="preview" class="mt-4">
    @if(old('cover_image'))
        <img src="{{ old('cover_image') }}" alt="Preview" class="max-w-xs rounded">
    @endif
</div>

<script>
document.getElementById('cover_image').addEventListener('change', function() {
    const preview = document.getElementById('preview');
    if (this.value) {
        preview.innerHTML = `<img src="${this.value}" alt="Preview" class="max-w-xs rounded">`;
    }
});
</script>
```

#### Using Blade Component (Easiest)

```blade
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',
    'value' => old('cover_image', $post->cover_image ?? ''),
    'label' => 'Cover Image',
    'size' => 'medium',
    'required' => true,
    'preview' => true
])
```

This single line replaces the entire file input with a MediaPanel-powered interface!

## Integration Examples

### Example 1: Blog Post Form

```blade
{{-- resources/views/posts/create.blade.php --}}
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    
    <div class="mb-6">
        <label>Title</label>
        <input type="text" name="title" class="w-full border rounded p-2">
    </div>
    
    {{-- Cover Image --}}
    <div class="mb-6">
        @include('mediapanel::components.media-input', [
            'name' => 'cover_image',
            'value' => old('cover_image'),
            'label' => 'Cover Image',
            'size' => 'large',
            'required' => true
        ])
    </div>
    
    {{-- OG Image --}}
    <div class="mb-6">
        @include('mediapanel::components.media-input', [
            'name' => 'og_image',
            'value' => old('og_image'),
            'label' => 'Open Graph Image',
            'size' => 'large'
        ])
    </div>
    
    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">
        Create Post
    </button>
</form>
```

### Example 2: With Rich Text Editor

```blade
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    
    <div class="mb-6">
        <label>Content</label>
        <textarea id="content" name="content">{{ old('content') }}</textarea>
        
        {{-- Insert Image Button --}}
        @include('mediapanel::components.media-button', [
            'targetEditor' => '#content',
            'size' => 'medium',
            'label' => 'Insert Image',
            'class' => 'mt-2 px-4 py-2 bg-indigo-600 text-white rounded-lg'
        ])
    </div>
    
    <button type="submit">Submit</button>
</form>

{{-- TinyMCE --}}
<script src="https://cdn.tiny.cloud/1/YOUR_KEY/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 400
});
</script>
```

### Example 3: Multiple Images

```blade
<form method="POST" action="{{ route('gallery.store') }}">
    @csrf
    
    <div class="mb-6">
        <label>Gallery Images</label>
        
        {{-- Hidden input for JSON array --}}
        <input type="hidden" name="images" id="gallery_images" value="{{ old('images', '[]') }}">
        
        {{-- MediaPanel Button --}}
        <button 
            type="button"
            data-mediapanel
            data-target-input="#gallery_images"
            data-multiple="true"
            data-size="medium"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
            Select Multiple Images
        </button>
        
        {{-- Preview Gallery --}}
        <div id="gallery-preview" class="grid grid-cols-4 gap-4 mt-4"></div>
    </div>
    
    <button type="submit">Save Gallery</button>
</form>

<script>
document.getElementById('gallery_images').addEventListener('change', function() {
    try {
        const images = JSON.parse(this.value || '[]');
        const preview = document.getElementById('gallery-preview');
        
        preview.innerHTML = images.map(img => 
            `<img src="${img.url}" alt="${img.title}" class="w-full h-32 object-cover rounded">`
        ).join('');
    } catch(e) {
        console.error('Invalid JSON:', e);
    }
});

// Custom handler for multiple selection
window.mediaPanelInstance.open({
    multiple: true,
    onSelect: function(mediaArray) {
        const urls = mediaArray.map(m => m.url);
        document.getElementById('gallery_images').value = JSON.stringify(
            mediaArray.map(m => ({ url: m.url, title: m.title }))
        );
        document.getElementById('gallery_images').dispatchEvent(new Event('change'));
    }
});
</script>
```

### Example 4: Settings Page

```blade
{{-- resources/views/admin/settings.blade.php --}}
<form method="POST" action="{{ route('settings.update') }}">
    @csrf
    
    <div class="space-y-6">
        {{-- Logo --}}
        <div>
            @include('mediapanel::components.media-input', [
                'name' => 'logo',
                'value' => old('logo', setting('logo')),
                'label' => 'Site Logo',
                'size' => 'original'
            ])
        </div>
        
        {{-- Favicon --}}
        <div>
            @include('mediapanel::components.media-input', [
                'name' => 'favicon',
                'value' => old('favicon', setting('favicon')),
                'label' => 'Favicon',
                'size' => 'original'
            ])
        </div>
        
        {{-- OG Image --}}
        <div>
            @include('mediapanel::components.media-input', [
                'name' => 'og_image',
                'value' => old('og_image', setting('og_image')),
                'label' => 'Default OG Image',
                'size' => 'large'
            ])
        </div>
    </div>
    
    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">
        Save Settings
    </button>
</form>
```

## JavaScript API

### Basic Usage

```javascript
// Open MediaPanel
window.mediaPanelInstance.open({
    targetInput: '#my_input',
    size: 'medium'
});
```

### With Callback

```javascript
window.mediaPanelInstance.open({
    onSelect: function(media) {
        console.log('Selected:', media);
        // media = { id: 1, url: '...', title: '...' }
        
        // Update your input
        document.getElementById('my_input').value = media.url;
        
        // Update preview
        document.getElementById('preview').innerHTML = 
            `<img src="${media.url}" alt="${media.title}">`;
    }
});
```

### Multiple Selection

```javascript
window.mediaPanelInstance.open({
    multiple: true,
    onSelect: function(mediaArray) {
        console.log('Selected:', mediaArray);
        // mediaArray = [{ id: 1, url: '...' }, { id: 2, url: '...' }]
        
        const urls = mediaArray.map(m => m.url).join(',');
        document.getElementById('images').value = urls;
    }
});
```

### Programmatic Control

```javascript
// Create new instance
const myMediaPanel = new MediaPanel({
    baseUrl: window.location.origin,
    csrfToken: document.querySelector('meta[name="csrf-token"]').content
});

// Open with custom options
myMediaPanel.open({
    targetInput: '#cover',
    size: 'large',
    multiple: false
});

// Close
myMediaPanel.close();

// Load specific folder
myMediaPanel.loadMedia(folderId = 1);

// Search
myMediaPanel.loadMedia(null, 'search term');
```

## Data Attributes Reference

### Button Attributes

```html
<button 
    data-mediapanel                    <!-- Required: Enable MediaPanel -->
    data-target-input="#input_id"      <!-- Input field selector -->
    data-target-editor="#editor_id"    <!-- Editor selector (TinyMCE, etc.) -->
    data-size="medium"                 <!-- Size: thumb, medium, large, original -->
    data-multiple="true">              <!-- Allow multiple selection -->
    Select Image
</button>
```

### Available Sizes

- `thumb` - 150x150px
- `medium` - 400x300px  
- `large` - 1200x800px
- `original` - Original size

## Component Parameters

### `media-input` Component

```blade
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',           // Input name (required)
    'value' => '',                      // Current value
    'label' => 'Cover Image',          // Label text
    'size' => 'medium',                 // Image size
    'required' => false,                // Required field
    'preview' => true                   // Show preview
])
```

### `media-button` Component

```blade
@include('mediapanel::components.media-button', [
    'targetInput' => '#input_id',      // Input selector
    'targetEditor' => '#editor_id',    // Editor selector
    'size' => 'medium',                 // Image size
    'multiple' => false,                // Multiple selection
    'label' => 'Select Image',         // Button text
    'class' => 'btn btn-primary'       // CSS classes
])
```

## Troubleshooting

### MediaPanel Not Opening

1. **Check CSRF Token:**
   ```blade
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```

2. **Check JavaScript is Loaded:**
   ```javascript
   console.log(window.mediaPanelInstance); // Should not be undefined
   ```

3. **Check Routes:**
   ```bash
   php artisan route:list | grep media
   ```

### Images Not Displaying

1. **Check Storage Link:**
   ```bash
   php artisan storage:link
   ```

2. **Check Permissions:**
   ```bash
   chmod -R 775 storage
   ```

3. **Check Config:**
   ```php
   // config/filesystems.php
   'default' => 'public',
   ```

### Preview Not Updating

Make sure you have the change event listener:

```javascript
document.getElementById('your_input_id').addEventListener('change', function() {
    // Update preview
    console.log('Value changed:', this.value);
});
```

## Migration from Old MediaPanel

If you're migrating from an older version:

### Old Way:
```blade
<a class="insert-media btn btn-info" 
   data-type="image" 
   data-for="display" 
   data-return="#cover_image" 
   data-link="cover-image">
   Add Image
</a>
```

### New Way:
```blade
<input type="hidden" name="cover_image" id="cover_image">
<button 
    type="button"
    data-mediapanel
    data-target-input="#cover_image"
    data-size="medium"
    class="btn btn-info">
    Add Image
</button>
```

Or simply use the component:
```blade
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',
    'size' => 'medium'
])
```

## Best Practices

1. **Always use hidden inputs** for form submission
2. **Include CSRF token** in your layout
3. **Use preview** for better UX
4. **Choose appropriate size** based on usage (thumb for thumbnails, large for hero images)
5. **Handle validation** on server side
6. **Use components** for consistency

## Complete Form Example

```blade
<form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
    @csrf
    
    {{-- Title --}}
    <div class="mb-6">
        <label class="block mb-2">Title</label>
        <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded p-2">
    </div>
    
    {{-- Cover Image --}}
    <div class="mb-6">
        @include('mediapanel::components.media-input', [
            'name' => 'cover_image',
            'value' => old('cover_image'),
            'label' => 'Cover Image',
            'size' => 'large',
            'required' => true
        ])
    </div>
    
    {{-- Content with Image Insertion --}}
    <div class="mb-6">
        <label class="block mb-2">Content</label>
        <textarea id="content" name="content">{{ old('content') }}</textarea>
        
        @include('mediapanel::components.media-button', [
            'targetEditor' => '#content',
            'size' => 'medium',
            'label' => 'Insert Image'
        ])
    </div>
    
    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">
        Create Post
    </button>
</form>
```

That's it! Your file inputs are now replaced with MediaPanel! 🎉

