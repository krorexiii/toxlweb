<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\WorkTranslationFactory> */
    use HasFactory;

    protected $fillable = [
        'work_id',
        'language_id',
        'title',
        'short_description',
        'description',
        'highlights',
    ];

    protected $casts = [
        'highlights' => 'array',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
