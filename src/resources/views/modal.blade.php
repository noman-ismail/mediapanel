{{-- MediaPanel Modal Body Content --}}
<!-- Search Bar -->
<div class="mediapanel-search-container">
    <input 
        type="text" 
        class="mediapanel-search" 
        placeholder="Search media..."
        id="mediapanel-search-input">
</div>

<!-- Upload Area -->
<div class="mediapanel-upload-area" id="mediapanel-upload-area">
    <input 
        type="file" 
        class="mediapanel-upload-input" 
        id="mediapanel-upload-input"
        accept="image/*">
    <label for="mediapanel-upload-input" class="mediapanel-upload-label">
        Upload New Image
    </label>
    <p class="text-sm text-gray-500 mt-2">
        PNG, JPG, GIF, WEBP up to {{ config('media.max_size', 5120) }}KB
    </p>
</div>

<!-- Media Grid -->
<div class="mediapanel-grid" id="mediapanel-grid">
    @forelse($media as $item)
        <div class="mediapanel-item" 
             data-id="{{ $item->id }}" 
             data-url="{{ $item->getUrl('original') }}">
            <div class="mediapanel-item-checkbox">
                <input type="radio" 
                       name="media-select" 
                       value="{{ $item->id }}"
                       data-url="{{ $item->getUrl('original') }}"
                       data-title="{{ $item->title ?? $item->name }}">
            </div>
            <img src="{{ $item->getUrl('thumb') }}" 
                 alt="{{ $item->alt ?? $item->title ?? $item->name }}"
                 loading="lazy">
            <div class="mediapanel-item-info">
                <p class="mediapanel-item-title">{{ $item->title ?? $item->name }}</p>
            </div>
        </div>
    @empty
        <div class="mediapanel-empty">
            <div class="mediapanel-empty-icon">📷</div>
            <p>No media files uploaded yet.</p>
        </div>
    @endforelse
</div>
