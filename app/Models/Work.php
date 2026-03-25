<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Work extends Model
{
    /** @use HasFactory<\Database\Factories\WorkFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'image_path',
        'gallery',
        'client_name',
        'location',
        'completed_at',
        'sort_order',
        'is_featured',
        'is_active',
        'metrics',
    ];

    protected $casts = [
        'completed_at' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'gallery' => 'array',
        'metrics' => 'array',
    ];

    public function translations()
    {
        return $this->hasMany(WorkTranslation::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function galleryImages(): array
    {
        $gallery = collect($this->gallery ?? [])
            ->map(fn ($image) => $this->resolveImageUrl($image))
            ->filter()
            ->values()
            ->all();

        if ($this->image_path) {
            array_unshift($gallery, $this->resolveImageUrl($this->image_path));
        }

        return array_values(array_unique(array_filter($gallery)));
    }

    public function resolveImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return asset('storage/'.$path);
    }
}
