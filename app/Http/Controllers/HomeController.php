<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $languages = Language::active()->get();
        $fallbackLanguage = $languages->firstWhere('is_default', true) ?? $languages->first();
        $selectedCode = $request->query('lang', $fallbackLanguage?->code ?? 'ar');
        $language = $languages->firstWhere('code', $selectedCode) ?? $fallbackLanguage;

        abort_unless($language, 404);

        $page = Page::query()
            ->where('slug', 'home')
            ->where('is_active', true)
            ->with([
                'sections' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['translations' => fn ($translationQuery) => $translationQuery->where('language_id', $language->id)]),
            ])
            ->firstOrFail();

        $products = Product::published()
            ->with(['translations' => fn ($query) => $query->where('language_id', $language->id)])
            ->get();

        $works = Work::published()
            ->with(['translations' => fn ($query) => $query->where('language_id', $language->id)])
            ->get();

        $settings = SiteSetting::current();
        $localizedSettings = data_get($settings->localized_content, $language->code, []);

        $sections = $page->sections->mapWithKeys(fn ($section) => [$section->key => $section]);

        return view('pages.home', compact(
            'language',
            'languages',
            'page',
            'sections',
            'products',
            'works',
            'settings',
            'localizedSettings',
        ));
    }
}
