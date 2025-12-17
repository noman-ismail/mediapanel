# MediaPanel Quick Start Guide

## Replace File Input in 30 Seconds

### Step 1: Include Assets (One Time)

Add to `resources/views/layouts/app.blade.php`:

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

### Step 2: Replace File Input

**Old way:**
```blade
<input type="file" name="cover_image">
```

**New way:**
```blade
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',
    'value' => old('cover_image'),
    'label' => 'Cover Image'
])
```

### Step 3: Done!

Users now see MediaPanel modal instead of browser file picker! 🎉

---

## Real-World Examples

### Blog Post Form

```blade
<form method="POST" action="/posts">
    @csrf
    
    {{-- Title --}}
    <input type="text" name="title">
    
    {{-- Cover Image (MediaPanel) --}}
    @include('mediapanel::components.media-input', [
        'name' => 'cover_image',
        'value' => old('cover_image'),
        'label' => 'Cover Image',
        'size' => 'large'
    ])
    
    {{-- Content Editor with Image Insert --}}
    <textarea id="content" name="content"></textarea>
    
    @include('mediapanel::components.media-button', [
        'targetEditor' => '#content',
        'label' => 'Insert Image'
    ])
    
    <button type="submit">Save</button>
</form>
```

### Settings Page

```blade
<form method="POST" action="/settings">
    @csrf
    
    {{-- Logo --}}
    @include('mediapanel::components.media-input', [
        'name' => 'logo',
        'value' => setting('logo'),
        'label' => 'Logo'
    ])
    
    {{-- Favicon --}}
    @include('mediapanel::components.media-input', [
        'name' => 'favicon',
        'value' => setting('favicon'),
        'label' => 'Favicon'
    ])
    
    <button type="submit">Save Settings</button>
</form>
```

### Multiple Images

```blade
<input type="hidden" name="gallery_images" id="gallery_images">

<button 
    type="button"
    data-mediapanel
    data-target-input="#gallery_images"
    data-multiple="true"
    data-size="medium">
    Select Multiple Images
</button>

<script>
document.getElementById('gallery_images').addEventListener('change', function() {
    const urls = this.value.split(',').filter(Boolean);
    console.log('Selected images:', urls);
});
</script>
```

---

## Component Parameters

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

---

## That's It!

MediaPanel replaces browser file inputs with a beautiful, searchable media library. No more browsing folders - users can search, preview, and select from uploaded images!

For more details, see [INTEGRATION_GUIDE.md](INTEGRATION_GUIDE.md)

