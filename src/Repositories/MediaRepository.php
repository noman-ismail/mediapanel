<?php

namespace NomanIsmail\MediaPanel\Repositories;

use NomanIsmail\MediaPanel\Models\Media;
use NomanIsmail\MediaPanel\Repositories\MediaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MediaRepository implements MediaRepositoryInterface
{
    /**
     * Store a new media record.
     *
     * @param array $data
     * @return Media
     */
    public function store(array $data): Media
    {
        return Media::create($data);
    }

    /**
     * Find media by ID.
     *
     * @param int $id
     * @return Media|null
     */
    public function find(int $id): ?Media
    {
        return Media::find($id);
    }

    /**
     * Get all media.
     *
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection
    {
        $query = Media::query();

        if (isset($filters['folder_id'])) {
            $query->where('folder_id', $filters['folder_id']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('alt', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Update media record.
     *
     * @param int $id
     * @param array $data
     * @return Media
     */
    public function update(int $id, array $data): Media
    {
        $media = $this->find($id);
        
        if (!$media) {
            throw new \Exception("Media not found");
        }

        $media->update($data);
        
        return $media->fresh();
    }

    /**
     * Delete media record.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $media = $this->find($id);
        
        if (!$media) {
            return false;
        }

        return $media->delete();
    }

    /**
     * Get media by folder.
     *
     * @param int|null $folderId
     * @return Collection
     */
    public function getByFolder(?int $folderId = null): Collection
    {
        $query = Media::query();

        if ($folderId === null) {
            $query->whereNull('folder_id');
        } else {
            $query->where('folder_id', $folderId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Search media by name or title.
     *
     * @param string $query
     * @return Collection
     */
    public function search(string $query): Collection
    {
        return Media::where('name', 'like', '%' . $query . '%')
            ->orWhere('title', 'like', '%' . $query . '%')
            ->orWhere('alt', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

