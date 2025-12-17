<?php

namespace NomanIsmail\MediaPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'path',
        'mime',
        'size',
        'width',
        'height',
        'folder_id',
        'alt',
        'title',
        'caption',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'folder_id' => 'integer',
    ];

    /**
     * Get the folder that owns the media.
     */
    public function folder()
    {
        return $this->belongsTo(MediaFolder::class);
    }

    /**
     * Get the full URL for the original image.
     *
     * @return string
     */
    public function getOriginalUrlAttribute()
    {
        $disk = config('media.disk', 'public');
        $path = $this->path ?: config('media.path', 'media');
        
        return Storage::disk($disk)->url($path . '/original/' . $this->name);
    }

    /**
     * Get the URL for a specific size.
     *
     * @param string $size
     * @return string
     */
    public function getUrl(string $size = 'original')
    {
        $disk = config('media.disk', 'public');
        $path = $this->path ?: config('media.path', 'media');
        
        if ($size === 'original') {
            return Storage::disk($disk)->url($path . '/original/' . $this->name);
        }

        $sizes = config('media.sizes', []);
        if (!isset($sizes[$size])) {
            return $this->original_url;
        }

        return Storage::disk($disk)->url($path . '/' . $size . '/' . $this->name);
    }

    /**
     * Get all available sizes for this media.
     *
     * @return array
     */
    public function getAvailableSizes()
    {
        $sizes = ['original'];
        $configSizes = config('media.sizes', []);
        $sizes = array_merge($sizes, array_keys($configSizes));
        
        return $sizes;
    }

    /**
     * Check if a specific size exists.
     *
     * @param string $size
     * @return bool
     */
    public function sizeExists(string $size)
    {
        $disk = config('media.disk', 'public');
        $path = $this->path ?: config('media.path', 'media');
        
        if ($size === 'original') {
            return Storage::disk($disk)->exists($path . '/original/' . $this->name);
        }

        return Storage::disk($disk)->exists($path . '/' . $size . '/' . $this->name);
    }

    /**
     * Delete all files associated with this media.
     *
     * @return bool
     */
    public function deleteFiles()
    {
        $disk = config('media.disk', 'public');
        $path = $this->path ?: config('media.path', 'media');
        $storage = Storage::disk($disk);
        $sizes = $this->getAvailableSizes();

        foreach ($sizes as $size) {
            $filePath = $size === 'original' 
                ? $path . '/original/' . $this->name
                : $path . '/' . $size . '/' . $this->name;

            if ($storage->exists($filePath)) {
                $storage->delete($filePath);
            }
        }

        return true;
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($media) {
            $media->deleteFiles();
        });
    }
}
