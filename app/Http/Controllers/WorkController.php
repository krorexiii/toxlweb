<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\SiteSetting;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkController extends Controller
{
    public function show(Request $request, Work $work): View
    {
        $languages = Language::active()->get();
        $fallbackLanguage = $languages->firstWhere('is_default', true) ?? $languages->first();
        $selectedCode = $request->query('lang', $fallbackLanguage?->code ?? 'ar');
        $language = $languages->firstWhere('code', $selectedCode) ?? $fallbackLanguage;

        abort_unless($language, 404);
        abort_unless($work->is_active, 404);

        $work->load([
            'translations' => fn ($query) => $query->where('language_id', $language->id),
        ]);

        $relatedWorks = Work::published()
            ->whereKeyNot($work->id)
            ->with(['translations' => fn ($query) => $query->where('language_id', $language->id)])
            ->limit(3)
            ->get();

        $settings = SiteSetting::current();
        $localizedSettings = data_get($settings->localized_content, $language->code, []);

        return view('pages.work-show', [
            'work' => $work,
            'translation' => $work->translations->first(),
            'relatedWorks' => $relatedWorks,
            'language' => $language,
            'languages' => $languages,
            'settings' => $settings,
            'localizedSettings' => $localizedSettings,
        ]);
    }
}
