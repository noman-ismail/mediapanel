<?php

namespace NomanIsmail\MediaPanel\Services;

use NomanIsmail\MediaPanel\Repositories\MediaRepositoryInterface;
use NomanIsmail\MediaPanel\Traits\ImageUploadTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class MediaService extends BaseService
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
                'path' => config('media.path', 'media'),
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

            return $this->success($media, 'Image uploaded successfully');
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Media upload failed');
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

            return $this->success($media, 'Media updated successfully');
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Media update failed');
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
                return $this->success(null, 'Media deleted successfully');
            }

            return $this->error('Media not found');
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Media delete failed');
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

            return $this->success($media);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to fetch media');
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

            return $this->success($media);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Media search failed');
        }
    }
}

