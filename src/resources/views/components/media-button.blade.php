{{--
    MediaPanel Button Component
    
    Usage:
    @include('mediapanel::components.media-button', [
        'targetInput' => '#cover_image',
        'size' => 'medium',
        'label' => 'Select Image',
        'class' => 'btn btn-primary'
    ])
--}}

@php
    $targetInput = $targetInput ?? '#media_input';
    $targetEditor = $targetEditor ?? null;
    $size = $size ?? 'original';
    $multiple = $multiple ?? false;
    $label = $label ?? 'Select Image';
    $class = $class ?? 'px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700';
    $buttonId = $buttonId ?? 'mediapanel-btn-' . uniqid();
@endphp

<button 
    type="button"
    id="{{ $buttonId }}"
    class="{{ $class }} mediapanel-trigger"
    data-mediapanel
    data-target-input="{{ $targetInput }}"
    @if($targetEditor) data-target-editor="{{ $targetEditor }}" @endif
    data-size="{{ $size }}"
    data-multiple="{{ $multiple ? 'true' : 'false' }}">
    {{ $label }}
</button>

