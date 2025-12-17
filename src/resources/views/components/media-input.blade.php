{{--
    MediaPanel Input Component - Replaces file input with MediaPanel
    
    Usage:
    @include('mediapanel::components.media-input', [
        'name' => 'cover_image',
        'value' => old('cover_image'),
        'label' => 'Cover Image',
        'size' => 'medium'
    ])
--}}

@php
    $name = $name ?? 'media_image';
    $value = $value ?? old($name);
    $label = $label ?? 'Select Image';
    $size = $size ?? 'original';
    $required = $required ?? false;
    $preview = $preview ?? true;
    $inputId = 'media-input-' . uniqid();
    $buttonId = 'media-button-' . uniqid();
@endphp

<div class="mediapanel-input-wrapper">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    
    <div class="flex gap-3 items-start">
        <!-- Hidden input for form submission -->
        <input 
            type="hidden" 
            name="{{ $name }}" 
            id="{{ $inputId }}"
            value="{{ $value }}"
            @if($required) required @endif>
        
        <!-- MediaPanel Button -->
        <button 
            type="button"
            id="{{ $buttonId }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors mediapanel-trigger"
            data-mediapanel
            data-target-input="#{{ $inputId }}"
            data-size="{{ $size }}">
            {{ $value ? 'Change Image' : 'Select Image' }}
        </button>
        
        <!-- Preview Image -->
        @if($preview && $value)
            <div class="mediapanel-preview">
                <img src="{{ $value }}" 
                     alt="Preview" 
                     class="max-w-xs h-32 object-cover rounded border border-gray-300"
                     id="preview-{{ $inputId }}">
                <button 
                    type="button"
                    onclick="document.getElementById('{{ $inputId }}').value = ''; document.getElementById('preview-{{ $inputId }}').style.display = 'none';"
                    class="ml-2 text-red-600 hover:text-red-800">
                    Remove
                </button>
            </div>
        @endif
    </div>
    
    <!-- Preview update script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('{{ $inputId }}');
            const preview = document.getElementById('preview-{{ $inputId }}');
            
            if (input && preview) {
                input.addEventListener('change', function() {
                    if (this.value) {
                        preview.src = this.value;
                        preview.style.display = 'block';
                        document.getElementById('{{ $buttonId }}').textContent = 'Change Image';
                    } else {
                        preview.style.display = 'none';
                        document.getElementById('{{ $buttonId }}').textContent = 'Select Image';
                    }
                });
            }
        });
    </script>
</div>

