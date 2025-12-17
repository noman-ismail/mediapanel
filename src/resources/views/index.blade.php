<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Media Panel</title>
    <link rel="stylesheet" href="{{ asset('vendor/mediapanel/mediapanel.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Media Panel</h1>

            <!-- Upload Form -->
            <form method="POST" action="{{ route('mediapanel.store') }}" enctype="multipart/form-data" class="mb-8" id="upload-form">
                @csrf
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-500 transition-colors">
                    <input type="file" name="image" id="image" class="hidden" accept="image/*">
                    <label for="image" class="cursor-pointer text-indigo-600 font-semibold hover:text-indigo-700">
                        Click to upload image
                    </label>
                    <p class="text-sm text-gray-500 mt-2">PNG, JPG, GIF, WEBP up to {{ config('media.max_size') }}KB</p>
                </div>
                <button type="submit" class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Upload
                </button>
            </form>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Media Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($media as $item)
                    <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <img src="{{ $item->getUrl('thumb') }}" 
                             alt="{{ $item->alt ?? $item->title ?? $item->name }}" 
                             class="w-full h-32 object-cover">
                        <div class="p-2">
                            <p class="text-xs text-gray-600 truncate">{{ $item->title ?? $item->name }}</p>
                            <div class="mt-2 flex gap-2">
                                <button onclick="copyUrl('{{ $item->getUrl('original') }}')" 
                                        class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Copy URL
                                </button>
                                <form method="POST" action="{{ route('mediapanel.destroy', $item->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure?')"
                                            class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        No media files uploaded yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/mediapanel/mediapanel.js') }}"></script>
    <script>
        // File validation
        document.addEventListener('change', function(e) {
            if (e.target.id === 'image') {
                const file = e.target.files[0];
                if (file && !file.type.startsWith('image/')) {
                    alert('Please select a valid image file');
                    e.target.value = '';
                }
            }
        });

        // Copy URL function
        function copyUrl(url) {
            navigator.clipboard.writeText(url).then(function() {
                alert('URL copied to clipboard!');
            });
        }
    </script>
</body>
</html>
