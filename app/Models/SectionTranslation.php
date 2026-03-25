<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\SectionTranslationFactory> */
    use HasFactory;

    protected $fillable = [
        'section_id',
        'language_id',
        'title',
        'subtitle',
        'body',
        'cta_primary_label',
        'cta_primary_url',
        'cta_secondary_label',
        'cta_secondary_url',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
