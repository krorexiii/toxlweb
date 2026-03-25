<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use App\Models\Product;
use App\Models\Section;
use App\Models\SiteSetting;
use App\Models\Work;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $setting = SiteSetting::query()->first();

        $stats = [
            'languages' => Language::query()->count(),
            'products' => Product::query()->count(),
            'works' => Work::query()->count(),
            'sections' => Section::query()->count(),
            'pages' => Page::query()->count(),
            'active_products' => Product::query()->where('is_active', true)->count(),
            'featured_works' => Work::query()->where('is_featured', true)->count(),
        ];

        $latestProducts = Product::query()
            ->latest('id')
            ->with('translations')
            ->take(5)
            ->get();

        $latestWorks = Work::query()
            ->latest('id')
            ->with('translations')
            ->take(5)
            ->get();

        $languages = Language::query()->orderBy('sort_order')->get();

        return view('dashboard', [
            'setting' => $setting,
            'stats' => $stats,
            'latestProducts' => $latestProducts,
            'latestWorks' => $latestWorks,
            'languages' => $languages,
        ]);
    }
}
