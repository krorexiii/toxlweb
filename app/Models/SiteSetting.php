<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    /** @use HasFactory<\Database\Factories\SiteSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_tagline',
        'logo_path',
        'favicon_path',
        'primary_phone',
        'secondary_phone',
        'primary_email',
        'address',
        'map_embed_url',
        'social_links',
        'localized_content',
    ];

    protected $casts = [
        'social_links' => 'array',
        'localized_content' => 'array',
    ];

    public static function current(): self
    {
        return static::query()->firstOrFail();
    }
}
