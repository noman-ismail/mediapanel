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
        return Storage::disk(config('media.disk'))->url($this->path . '/original/' . $this->name);
    }

    /**
     * Get the URL for a specific size.
     *
     * @param string $size
     * @return string
     */
    public function getUrl(string $size = 'original')
    {
        if ($size === 'original') {
            return $this->original_url;
        }

        $sizes = config('media.sizes');
        if (!isset($sizes[$size])) {
            return $this->original_url;
        }

        return Storage::disk(config('media.disk'))->url($this->path . '/' . $size . '/' . $this->name);
    }

    /**
     * Get all available sizes for this media.
     *
     * @return array
     */
    public function getAvailableSizes()
    {
        $sizes = ['original'];
        $sizes = array_merge($sizes, array_keys(config('media.sizes')));
        
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
        if ($size === 'original') {
            return Storage::disk(config('media.disk'))->exists($this->path . '/original/' . $this->name);
        }

        return Storage::disk(config('media.disk'))->exists($this->path . '/' . $size . '/' . $this->name);
    }

    /**
     * Delete all files associated with this media.
     *
     * @return bool
     */
    public function deleteFiles()
    {
        $disk = Storage::disk(config('media.disk'));
        $sizes = $this->getAvailableSizes();

        foreach ($sizes as $size) {
            $filePath = $size === 'original' 
                ? $this->path . '/original/' . $this->name
                : $this->path . '/' . $size . '/' . $this->name;

            if ($disk->exists($filePath)) {
                $disk->delete($filePath);
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
