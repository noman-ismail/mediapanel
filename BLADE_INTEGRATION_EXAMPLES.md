# MediaPanel - Blade Integration Examples

Complete examples for integrating MediaPanel into any Blade project.

## 🎯 Quick Integration (3 Steps)

### Step 1: Add Assets to Layout

```blade
{{-- resources/views/layouts/app.blade.php --}}
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">
</head>
<body>
    @yield('content')
    <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
</body>
```

### Step 2: Use Component in Form

```blade
@include('mediapanel::components.media-input', [
    'name' => 'cover_image',
    'label' => 'Cover Image'
])
```

### Step 3: Done! ✅

MediaPanel is now integrated.

---

## 📝 Real-World Examples

### Example 1: Blog Post Form

```blade
{{-- resources/views/posts/create.blade.php --}}
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    
    {{-- MediaPanel Integration --}}
    <div class="form-group">
        @include('mediapanel::components.media-input', [
            'name' => 'cover_image',
            'value' => old('cover_image'),
            'label' => 'Cover Image',
            'size' => 'large',
            'required' => true
        ])
    </div>
    
    <div class="form-group">
        <label>Content</label>
        <textarea name="content" class="form-control" rows="10"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Create Post</button>
</form>
```

### Example 2: User Profile Form

```blade
{{-- resources/views/profile/edit.blade.php --}}
<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <h2>Edit Profile</h2>
    
    {{-- Profile Picture --}}
    <div class="mb-4">
        @include('mediapanel::components.media-input', [
            'name' => 'avatar',
            'value' => old('avatar', auth()->user()->avatar),
            'label' => 'Profile Picture',
            'size' => 'medium',
            'preview' => true
        ])
    </div>
    
    <div class="mb-4">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}">
    </div>
    
    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>
```

### Example 3: Product Form

```blade
{{-- resources/views/products/create.blade.php --}}
<form method="POST" action="{{ route('products.store') }}">
    @csrf
    
    <div class="mb-4">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    {{-- Product Image --}}
    <div class="mb-4">
        @include('mediapanel::components.media-input', [
            'name' => 'image',
            'value' => old('image'),
            'label' => 'Product Image',
            'size' => 'large',
            'required' => true
        ])
    </div>
    
    {{-- Gallery Images --}}
    <div class="mb-4">
        <label>Gallery Images</label>
        <input type="hidden" name="gallery" id="gallery" value="{{ old('gallery') }}">
        
        @include('mediapanel::components.media-button', [
            'targetInput' => '#gallery',
            'multiple' => true,
            'size' => 'medium',
            'label' => 'Select Gallery Images',
            'class' => 'btn btn-secondary'
        ])
        
        <div id="gallery-preview" class="mt-4 grid grid-cols-4 gap-2">
            @if(old('gallery'))
                @foreach(explode(',', old('gallery')) as $url)
                    @if($url)
                        <img src="{{ $url }}" alt="Preview" class="w-full h-24 object-cover rounded">
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Create Product</button>
</form>

<script>
document.getElementById('gallery').addEventListener('change', function() {
    const urls = this.value.split(',').filter(Boolean);
    const preview = document.getElementById('gallery-preview');
    preview.innerHTML = urls.map(url => 
        `<img src="${url}" alt="Preview" class="w-full h-24 object-cover rounded">`
    ).join('');
});
</script>
```

### Example 4: Settings Page

```blade
{{-- resources/views/admin/settings.blade.php --}}
<form method="POST" action="{{ route('settings.update') }}">
    @csrf
    @method('PUT')
    
    <h2>Site Settings</h2>
    
    {{-- Logo --}}
    <div class="mb-6">
        <label class="block mb-2">Site Logo</label>
        @include('mediapanel::components.media-input', [
            'name' => 'logo',
            'value' => setting('logo'),
            'label' => 'Upload Logo',
            'size' => 'medium'
        ])
    </div>
    
    {{-- Favicon --}}
    <div class="mb-6">
        <label class="block mb-2">Favicon</label>
        @include('mediapanel::components.media-input', [
            'name' => 'favicon',
            'value' => setting('favicon'),
            'label' => 'Upload Favicon',
            'size' => 'thumb'
        ])
    </div>
    
    {{-- Hero Image --}}
    <div class="mb-6">
        <label class="block mb-2">Hero Image</label>
        @include('mediapanel::components.media-input', [
            'name' => 'hero_image',
            'value' => setting('hero_image'),
            'label' => 'Upload Hero Image',
            'size' => 'large'
        ])
    </div>
    
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
```

### Example 5: With TinyMCE Editor

```blade
{{-- resources/views/posts/edit.blade.php --}}
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
                'class' => 'btn btn-sm btn-secondary'
            ])
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Update Post</button>
</form>

<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 400,
    plugins: 'image link lists',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image'
});
</script>
```

### Example 6: With CKEditor

```blade
<form method="POST" action="{{ route('posts.store') }}">
    @csrf
    
    <div class="mb-4">
        <label>Content</label>
        <textarea id="content" name="content"></textarea>
        
        @include('mediapanel::components.media-button', [
            'targetEditor' => '#content',
            'size' => 'medium',
            'label' => 'Insert Image'
        ])
    </div>
    
    <button type="submit">Submit</button>
</form>

<script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('content');
</script>
```

### Example 7: Category Form with Image

```blade
{{-- resources/views/categories/create.blade.php --}}
<form method="POST" action="{{ route('categories.store') }}">
    @csrf
    
    <div class="mb-4">
        <label>Category Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    <div class="mb-4">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    
    {{-- Category Image --}}
    <div class="mb-4">
        @include('mediapanel::components.media-input', [
            'name' => 'image',
            'value' => old('image'),
            'label' => 'Category Image',
            'size' => 'medium'
        ])
    </div>
    
    <button type="submit" class="btn btn-primary">Create Category</button>
</form>
```

### Example 8: Page Builder

```blade
{{-- resources/views/pages/create.blade.php --}}
<form method="POST" action="{{ route('pages.store') }}">
    @csrf
    
    <div class="mb-4">
        <label>Page Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    
    {{-- Featured Image --}}
    <div class="mb-4">
        @include('mediapanel::components.media-input', [
            'name' => 'featured_image',
            'value' => old('featured_image'),
            'label' => 'Featured Image',
            'size' => 'large'
        ])
    </div>
    
    {{-- Banner Image --}}
    <div class="mb-4">
        @include('mediapanel::components.media-input', [
            'name' => 'banner_image',
            'value' => old('banner_image'),
            'label' => 'Banner Image',
            'size' => 'large'
        ])
    </div>
    
    <div class="mb-4">
        <label>Content</label>
        <textarea name="content" class="form-control" rows="10"></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Create Page</button>
</form>
```

## 🔧 Advanced Usage

### Custom Callback

```blade
<button 
    type="button"
    id="custom-media-btn"
    class="btn btn-primary">
    Select Image
</button>

<script>
document.getElementById('custom-media-btn').addEventListener('click', function() {
    window.mediaPanelInstance.open({
        size: 'medium',
        onSelect: function(media) {
            // Custom handling
            console.log('Selected:', media);
            
            // Update your custom field
            document.getElementById('custom-field').value = media.url;
            
            // Show preview
            document.getElementById('preview').innerHTML = 
                `<img src="${media.url}" alt="${media.title}">`;
        }
    });
});
</script>
```

### Multiple Selection

```blade
<input type="hidden" name="images" id="images">

<button 
    type="button"
    data-mediapanel
    data-target-input="#images"
    data-multiple="true"
    data-size="medium"
    class="btn btn-primary">
    Select Multiple Images
</button>

<script>
document.getElementById('images').addEventListener('change', function() {
    const urls = this.value.split(',').filter(Boolean);
    console.log('Selected images:', urls);
    
    // Update preview
    const preview = document.getElementById('preview');
    preview.innerHTML = urls.map(url => 
        `<img src="${url}" alt="Preview" class="w-24 h-24 object-cover rounded m-2">`
    ).join('');
});
</script>
```

## ✅ Integration Checklist

- [ ] Install package: `composer require nomanismail/mediapanel`
- [ ] Publish migrations: `php artisan vendor:publish --tag=mediapanel-migrations`
- [ ] Run migrations: `php artisan migrate`
- [ ] Publish assets: `php artisan vendor:publish --tag=mediapanel-assets`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Add CSRF token to layout: `<meta name="csrf-token" content="{{ csrf_token() }}">`
- [ ] Add CSS to layout: `<link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">`
- [ ] Add JS to layout: `<script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>`
- [ ] Use component in forms: `@include('mediapanel::components.media-input')`
- [ ] Test upload functionality

## 🎉 Result

After integration:
- ✅ File inputs replaced with MediaPanel modal
- ✅ Users can upload and manage images
- ✅ Images automatically resized (thumb, medium, large)
- ✅ Search functionality available
- ✅ Folder organization supported
- ✅ Works with any Blade project

**MediaPanel is now integrated and ready to use!** 🚀

