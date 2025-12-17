# MediaPanel - Laravel Media Management Package

[![Latest Version](https://img.shields.io/packagist/v/nomanismail/mediapanel.svg?style=flat-square)](https://packagist.org/packages/nomanismail/mediapanel)
[![Total Downloads](https://img.shields.io/packagist/dt/nomanismail/mediapanel.svg?style=flat-square)](https://packagist.org/packages/nomanismail/mediapanel)
[![License](https://img.shields.io/packagist/l/nomanismail/mediapanel.svg?style=flat-square)](https://packagist.org/packages/nomanismail/mediapanel)

A modern, clean architecture Laravel package for media file management with image resizing, multiple size generation, and a beautiful Tailwind CSS UI. Replace browser file inputs with a searchable media library modal.

## ✨ Features

- 🎨 **Modern UI** - Beautiful Tailwind CSS interface (no Bootstrap dependency)
- 🖼️ **Multi-size Processing** - Automatic generation of thumb, medium, and large sizes
- 📁 **Folder Organization** - Organize media files into folders
- 🔍 **Search & Filter** - Built-in search functionality
- 📤 **Drag & Drop Upload** - Easy file upload with drag and drop support
- 🎯 **Framework Agnostic** - Works with Blade, React, Vue, or any JavaScript framework
- 🔌 **Editor Integration** - Seamless integration with TinyMCE, CKEditor, Quill
- ⚡ **Clean Architecture** - Controller → Service → Repository → Trait pattern
- 🎛️ **Highly Configurable** - Customize sizes, quality, storage disk via config
- 🚀 **Laravel 12+ Ready** - Built for Laravel 12 and PHP 8.3+

## 📋 Requirements

- PHP >= 8.3
- Laravel >= 12.0
- Intervention Image >= 3.0
- Tailwind CSS (for frontend styling)

## 📦 Installation

### Step 1: Install via Composer

```bash
composer require nomanismail/mediapanel
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --tag=mediapanel-config
```

This will create `config/media.php` in your project.

### Step 3: Publish Migrations

```bash
php artisan vendor:publish --tag=mediapanel-migrations
php artisan migrate
```

### Step 4: Create Storage Link

```bash
php artisan storage:link
```

### Step 5: Publish Assets (Optional)

```bash
php artisan vendor:publish --tag=mediapanel-assets
```

This copies CSS and JavaScript files to `public/vendor/mediapanel/` for customization.

## ⚙️ Configuration

Edit `config/media.php`:

```php
return [
    'disk' => 'public',                    // Storage disk
    'sizes' => [
        'thumb' => [150, 150],            // Thumbnail size
        'medium' => [400, 300],           // Medium size
        'large' => [1200, 800],           // Large size
    ],
    'path' => 'media',                    // Storage path
    'quality' => 85,                      // Image quality (1-100)
    'max_size' => 5120,                   // Max file size in KB
    'auto_webp' => false,                 // Auto-convert to WebP
    'folders_enabled' => true,            // Enable folder organization
];
```

## 🚀 Quick Start

### 1. Include Assets in Your Layout

Add to `resources/views/layouts/app.blade.php`:

```blade
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">
</head>
<body>
    {{-- Your content --}}
    <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
</body>
```

### 2. Replace File Input with MediaPanel

**Before:**
```blade
<input type="file" name="cover_image" id="cover_image">
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

**That's it!** Users now see MediaPanel modal instead of browser file picker! 🎉

## 📖 Usage Examples

### Basic Form Integration

```blade
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    
    {{-- Title --}}
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
    
    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">
        Create Post
    </button>
</form>
```

### Rich Text Editor Integration

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

<script src="https://cdn.tiny.cloud/1/YOUR_KEY/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 400
});
</script>
```

### Manual Integration

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

{{-- Preview --}}
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

### Multiple Image Selection

```blade
<input type="hidden" name="gallery_images" id="gallery_images">

<button 
    type="button"
    data-mediapanel
    data-target-input="#gallery_images"
    data-multiple="true"
    data-size="medium"
    class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
    Select Multiple Images
</button>

<script>
document.getElementById('gallery_images').addEventListener('change', function() {
    const urls = this.value.split(',').filter(Boolean);
    console.log('Selected images:', urls);
});
</script>
```

### JavaScript API

```javascript
// Open MediaPanel programmatically
window.mediaPanelInstance.open({
    targetInput: '#my_input',
    size: 'medium',
    onSelect: function(media) {
        console.log('Selected:', media);
        // media = { id: 1, url: '...', title: '...' }
        document.getElementById('my_input').value = media.url;
    }
});

// Multiple selection
window.mediaPanelInstance.open({
    multiple: true,
    onSelect: function(mediaArray) {
        console.log('Selected:', mediaArray);
        // mediaArray = [{ id: 1, url: '...' }, { id: 2, url: '...' }]
    }
});
```

## 🎯 Component Parameters

### `media-input` Component

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `name` | string | required | Input field name |
| `value` | string | '' | Current image URL |
| `label` | string | 'Select Image' | Label text |
| `size` | string | 'original' | Image size (thumb/medium/large/original) |
| `required` | bool | false | Required field |
| `preview` | bool | true | Show preview image |

### `media-button` Component

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `targetInput` | string | null | Input selector (#id) |
| `targetEditor` | string | null | Editor selector (#id) |
| `size` | string | 'original' | Image size |
| `multiple` | bool | false | Multiple selection |
| `label` | string | 'Select Image' | Button text |
| `class` | string | '' | CSS classes |

## 🔧 Data Attributes

```html
<button 
    data-mediapanel                    <!-- Required: Enable MediaPanel -->
    data-target-input="#input_id"      <!-- Input field selector -->
    data-target-editor="#editor_id"    <!-- Editor selector -->
    data-size="medium"                  <!-- Size: thumb, medium, large, original -->
    data-multiple="true">               <!-- Allow multiple selection -->
    Select Image
</button>
```

## 📚 API Methods

### PHP Usage

```php
use NomanIsmail\MediaPanel\Services\MediaService;
use NomanIsmail\MediaPanel\Models\Media;

// Upload image
$result = $mediaService->upload(
    $request->file('image'),
    [
        'title' => 'My Image',
        'alt' => 'Image description',
        'folder_id' => 1,
    ]
);

// Get image URLs
$media = Media::find(1);
$thumbUrl = $media->getUrl('thumb');
$mediumUrl = $media->getUrl('medium');
$largeUrl = $media->getUrl('large');
$originalUrl = $media->original_url;
```

### JavaScript API

```javascript
// Global instance
window.mediaPanelInstance.open(options);
window.mediaPanelInstance.close();
window.mediaPanelInstance.loadMedia(folderId, search);

// Create custom instance
const mediaPanel = new MediaPanel({
    baseUrl: window.location.origin,
    csrfToken: document.querySelector('meta[name="csrf-token"]').content
});
```

## 🗂️ Storage Structure

```
storage/app/public/media/
├── original/
│   ├── 20240101120000_abc123.jpg
│   └── ...
├── thumb/
│   ├── 20240101120000_abc123.jpg
│   └── ...
├── medium/
│   ├── 20240101120000_abc123.jpg
│   └── ...
└── large/
    ├── 20240101120000_abc123.jpg
    └── ...
```

## 🎨 Customization

### Custom Image Sizes

Edit `config/media.php`:

```php
'sizes' => [
    'thumb' => [150, 150],
    'medium' => [400, 300],
    'large' => [1200, 800],
    'custom' => [800, 600],  // Add your custom size
],
```

### Custom Storage Disk

```php
'disk' => 's3',  // Use S3 or any other disk
```

### Custom CSS

Publish assets and customize:

```bash
php artisan vendor:publish --tag=mediapanel-assets
```

Then edit `public/vendor/mediapanel/mediapanel.css`

## 📖 Documentation

- [Integration Guide](INTEGRATION_GUIDE.md) - Complete integration examples
- [Quick Start](QUICK_START.md) - 30-second quick start guide
- [API Reference](docs/API.md) - Full API documentation

## 🧪 Testing

```bash
composer test
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more information on what has changed recently.

## 🐛 Known Issues

- None at the moment. Please report any issues you find!

## 🔒 Security

If you discover any security-related issues, please email nomanismail@example.com instead of using the issue tracker.

## 📄 License

The MIT License (MIT). Please see [LICENSE](LICENSE) file for more information.

## 👤 Author

**Noman Ismail**

- GitHub: [@noman-ismail](https://github.com/noman-ismail)
- Email: nomanismail@example.com

## 🙏 Acknowledgments

- [Intervention Image](https://github.com/Intervention/image) for image processing
- [Laravel](https://laravel.com) for the amazing framework
- [Tailwind CSS](https://tailwindcss.com) for the beautiful UI

## ⭐ Show Your Support

Give a ⭐️ if this project helped you!

## 📞 Support

For support, email nomanismail@example.com or create an issue in the [GitHub repository](https://github.com/noman-ismail/mediapanel/issues).

---

Made with ❤️ by [Noman Ismail](https://github.com/noman-ismail)
