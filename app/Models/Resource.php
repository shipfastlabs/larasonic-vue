<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Resource extends Model
{
    protected $fillable = [
        'type',
        'file_path',
        'file_name',
        'mime_type',
        'disk',
        'file_size',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the parent resourceable model.
     */
    public function resourceable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full storage path of the resource
     */
    public function getFullPathAttribute(): string
    {
        return storage_path("app/{$this->disk}/".$this->file_path);
    }

    /**
     * Get the URL to access the resource
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->file_path);
    }

    /**
     * Get a specific metadata value
     */
    public function getMetadata(string $key, $default = null)
    {
        return data_get($this->metadata, $key, $default);
    }
}
