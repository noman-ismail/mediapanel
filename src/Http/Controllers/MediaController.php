<?php

namespace NomanIsmail\MediaPanel\Http\Controllers;

use NomanIsmail\MediaPanel\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class MediaController extends Controller
{
    /**
     * Media service instance.
     *
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * Create a new controller instance.
     *
     * @param MediaService $mediaService
     */
    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Display the media panel.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $filters = request()->only(['folder_id', 'search']);
        $result = $this->mediaService->getAll($filters);
        $media = $result['data'] ?? [];

        // If AJAX request, return modal HTML
        if (request()->expectsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'html' => view('mediapanel::modal', compact('media'))->render()
            ]);
        }

        // Otherwise return full page
        return view('mediapanel::index', compact('media'));
    }

    /**
     * Store a newly uploaded image.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:' . (config('media.max_size') * 1024),
            'folder_id' => 'nullable|integer|exists:media_folders,id',
            'title' => 'nullable|string|max:255',
            'alt' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $metadata = $request->only(['folder_id', 'title', 'alt', 'caption', 'description']);
        $result = $this->mediaService->upload($request->file('image'), $metadata);

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Update media metadata.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'alt' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'folder_id' => 'nullable|integer|exists:media_folders,id',
        ]);

        $result = $this->mediaService->update($id, $request->only([
            'title', 'alt', 'caption', 'description', 'folder_id'
        ]));

        return response()->json($result);
    }

    /**
     * Delete media.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $result = $this->mediaService->delete($id);

        return response()->json($result);
    }

    /**
     * Search media.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $result = $this->mediaService->search($request->input('query'));

        return response()->json($result);
    }

    /**
     * Get media by folder.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getByFolder(Request $request)
    {
        $folderId = $request->input('folder_id');
        $filters = ['folder_id' => $folderId];
        $result = $this->mediaService->getAll($filters);

        return response()->json($result);
    }
}

