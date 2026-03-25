<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTranslationFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'language_id',
        'name',
        'short_description',
        'description',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
