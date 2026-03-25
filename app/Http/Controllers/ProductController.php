<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Request $request, Product $product): View
    {
        $languages = Language::active()->get();
        $fallbackLanguage = $languages->firstWhere('is_default', true) ?? $languages->first();
        $selectedCode = $request->query('lang', $fallbackLanguage?->code ?? 'ar');
        $language = $languages->firstWhere('code', $selectedCode) ?? $fallbackLanguage;

        abort_unless($language, 404);
        abort_unless($product->is_active, 404);

        $product->load([
            'translations' => fn ($query) => $query->where('language_id', $language->id),
        ]);

        $relatedProducts = Product::published()
            ->whereKeyNot($product->id)
            ->with(['translations' => fn ($query) => $query->where('language_id', $language->id)])
            ->limit(3)
            ->get();

        $settings = SiteSetting::current();
        $localizedSettings = data_get($settings->localized_content, $language->code, []);

        return view('pages.product-show', [
            'product' => $product,
            'translation' => $product->translations->first(),
            'relatedProducts' => $relatedProducts,
            'language' => $language,
            'languages' => $languages,
            'settings' => $settings,
            'localizedSettings' => $localizedSettings,
        ]);
    }
}
