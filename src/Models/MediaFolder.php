<?php

namespace NomanIsmail\MediaPanel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaFolder extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media_folders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    /**
     * Get the parent folder.
     */
    public function parent()
    {
        return $this->belongsTo(MediaFolder::class, 'parent_id');
    }

    /**
     * Get child folders.
     */
    public function children()
    {
        return $this->hasMany(MediaFolder::class, 'parent_id');
    }

    /**
     * Get media in this folder.
     */
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Generate slug from name.
     *
     * @param string $name
     * @return string
     */
    public static function generateSlug(string $name)
    {
        return \Illuminate\Support\Str::slug($name);
    }
}
