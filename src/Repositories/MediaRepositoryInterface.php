<?php

namespace NomanIsmail\MediaPanel\Repositories;

use NomanIsmail\MediaPanel\Models\Media;
use Illuminate\Database\Eloquent\Collection;

interface MediaRepositoryInterface
{
    /**
     * Store a new media record.
     *
     * @param array $data
     * @return Media
     */
    public function store(array $data): Media;

    /**
     * Find media by ID.
     *
     * @param int $id
     * @return Media|null
     */
    public function find(int $id): ?Media;

    /**
     * Get all media.
     *
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection;

    /**
     * Update media record.
     *
     * @param int $id
     * @param array $data
     * @return Media
     */
    public function update(int $id, array $data): Media;

    /**
     * Delete media record.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get media by folder.
     *
     * @param int|null $folderId
     * @return Collection
     */
    public function getByFolder(?int $folderId = null): Collection;

    /**
     * Search media by name or title.
     *
     * @param string $query
     * @return Collection
     */
    public function search(string $query): Collection;
}

