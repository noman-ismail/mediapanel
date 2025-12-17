<?php

namespace NomanIsmail\MediaPanel\Services;

use NomanIsmail\MediaPanel\Repositories\MediaRepositoryInterface;
use NomanIsmail\MediaPanel\Traits\ImageUploadTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class MediaService
{
    use ImageUploadTrait;

    /**
     * Media repository instance.
     *
     * @var MediaRepositoryInterface
     */
    protected MediaRepositoryInterface $mediaRepository;

    /**
     * Create a new media service instance.
     *
     * @param MediaRepositoryInterface $mediaRepository
     */
    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Upload and process an image file.
     *
     * @param UploadedFile $file
     * @param array $metadata
     * @return array
     */
    public function upload(UploadedFile $file, array $metadata = []): array
    {
        try {
            // Validate image
            $this->validateImage($file);

            // Generate unique filename
            $name = $this->generateFileName($file);

            // Process image and create multiple sizes
            $dimensions = $this->processImage($file, $name);

            // Store media record
            $media = $this->mediaRepository->store([
                'name' => $name,
                'path' => config('media.path'),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $dimensions['width'],
                'height' => $dimensions['height'],
                'folder_id' => $metadata['folder_id'] ?? null,
                'title' => $metadata['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'alt' => $metadata['alt'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'caption' => $metadata['caption'] ?? null,
                'description' => $metadata['description'] ?? null,
            ]);

            return [
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => $media,
            ];
        } catch (\Throwable $e) {
            Log::error('Media upload failed: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Media upload failed: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Update media metadata.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function update(int $id, array $data): array
    {
        try {
            $media = $this->mediaRepository->update($id, $data);

            return [
                'success' => true,
                'message' => 'Media updated successfully',
                'data' => $media,
            ];
        } catch (\Throwable $e) {
            Log::error('Media update failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Media update failed: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Delete media and associated files.
     *
     * @param int $id
     * @return array
     */
    public function delete(int $id): array
    {
        try {
            $deleted = $this->mediaRepository->delete($id);

            if ($deleted) {
                return [
                    'success' => true,
                    'message' => 'Media deleted successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Media not found',
            ];
        } catch (\Throwable $e) {
            Log::error('Media delete failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Media delete failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get all media with optional filters.
     *
     * @param array $filters
     * @return array
     */
    public function getAll(array $filters = []): array
    {
        try {
            $media = $this->mediaRepository->all($filters);

            return [
                'success' => true,
                'data' => $media,
            ];
        } catch (\Throwable $e) {
            Log::error('Media fetch failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to fetch media',
                'data' => [],
            ];
        }
    }

    /**
     * Search media.
     *
     * @param string $query
     * @return array
     */
    public function search(string $query): array
    {
        try {
            $media = $this->mediaRepository->search($query);

            return [
                'success' => true,
                'data' => $media,
            ];
        } catch (\Throwable $e) {
            Log::error('Media search failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Search failed',
                'data' => [],
            ];
        }
    }
}

