<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'sku',
        'image_path',
        'gallery',
        'sort_order',
        'is_featured',
        'is_active',
        'specifications',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'gallery' => 'array',
        'specifications' => 'array',
    ];

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
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
