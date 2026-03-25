<!DOCTYPE html>
<html lang="{{ $language->code }}" dir="{{ $language->code === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $translation?->title }} | {{ $settings->site_name }}</title>
        <meta name="description" content="{{ $translation?->short_description }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 text-zinc-100 antialiased">
        @php
            $isArabic = $language->code === 'ar';
            $gallery = $work->galleryImages();
            $address = data_get($localizedSettings, 'address', $settings->address);
        @endphp

        <header class="sticky top-0 z-50 backdrop-blur bg-zinc-950/80 border-b border-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                <a href="{{ route('home', ['lang' => $language->code]) }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-yellow-400 text-zinc-900 font-black grid place-items-center overflow-hidden">
                        <span>{{ str($settings->site_name)->substr(0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-bold text-lg leading-none">{{ $settings->site_name }}</p>
                        <p class="text-xs text-zinc-400">{{ data_get($localizedSettings, 'tagline', $settings->site_tagline) }}</p>
                    </div>
                </a>

                <div class="flex items-center gap-2">
                    @foreach ($languages as $navLanguage)
                        <a href="{{ route('works.show', ['work' => $work->slug, 'lang' => $navLanguage->code]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $navLanguage->is($language) ? 'bg-yellow-400 text-zinc-950' : 'bg-zinc-900 text-zinc-200 border border-zinc-800' }}">
                            {{ strtoupper($navLanguage->code) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
            <div class="mb-8 flex flex-wrap items-center gap-3 text-sm text-zinc-400">
                <a href="{{ route('home', ['lang' => $language->code]) }}" class="hover:text-yellow-400">{{ $isArabic ? 'الرئيسية' : 'Home' }}</a>
                <span>/</span>
                <a href="{{ route('home', ['lang' => $language->code]) }}#works" class="hover:text-yellow-400">{{ $isArabic ? 'الأعمال' : 'Works' }}</a>
                <span>/</span>
                <span class="text-zinc-100">{{ $translation?->title }}</span>
            </div>

            <section class="grid lg:grid-cols-2 gap-10 items-start">
                <div class="space-y-4">
                    <div class="rounded-3xl overflow-hidden border border-zinc-800 bg-zinc-900/50">
                        <img src="{{ $gallery[0] ?? 'https://placehold.co/1200x900/18181b/facc15?text=Work' }}" alt="{{ $translation?->title }}" class="w-full h-[420px] md:h-[520px] object-cover">
                    </div>

                    @if (count($gallery) > 1)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($gallery as $image)
                                <div class="rounded-2xl overflow-hidden border border-zinc-800 bg-zinc-900/50">
                                    <img src="{{ $image }}" alt="{{ $translation?->title }} gallery image" class="w-full h-32 object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <div class="flex flex-wrap gap-3 items-center text-sm mb-4">
                        <span class="rounded-full bg-yellow-400 px-3 py-1 font-semibold text-zinc-950">{{ $work->location }}</span>
                        <span class="rounded-full border border-zinc-700 bg-zinc-900 px-3 py-1 text-zinc-200">{{ $work->client_name }}</span>
                    </div>

                    <h1 class="text-4xl font-extrabold">{{ $translation?->title }}</h1>
                    <p class="mt-4 text-lg text-zinc-300">{{ $translation?->short_description }}</p>

                    <div class="mt-6 rounded-3xl border border-zinc-800 bg-zinc-900/50 p-6">
                        <h2 class="text-xl font-semibold mb-4">{{ $isArabic ? 'تفاصيل العمل' : 'Work details' }}</h2>
                        <p class="text-zinc-300 leading-8">{{ $translation?->description }}</p>
                    </div>

                    @if ($translation?->highlights)
                        <div class="mt-6 rounded-3xl border border-zinc-800 bg-zinc-900/50 p-6">
                            <h2 class="text-xl font-semibold mb-4">{{ $isArabic ? 'أبرز النقاط' : 'Highlights' }}</h2>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($translation->highlights as $highlight)
                                    <span class="rounded-full border border-yellow-400/20 bg-yellow-400/10 px-4 py-2 text-sm text-yellow-300">{{ $highlight }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($work->metrics)
                        <div class="mt-6 rounded-3xl border border-zinc-800 bg-zinc-900/50 overflow-hidden">
                            <div class="p-6 border-b border-zinc-800">
                                <h2 class="text-xl font-semibold">{{ $isArabic ? 'المؤشرات' : 'Metrics' }}</h2>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-px bg-zinc-800">
                                @foreach ($work->metrics as $metric)
                                    <div class="bg-zinc-900 p-6">
                                        <p class="text-sm text-zinc-400">{{ data_get($metric, 'label') }}</p>
                                        <p class="mt-2 text-2xl font-bold text-white">{{ data_get($metric, 'value') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 rounded-3xl border border-zinc-800 bg-zinc-900/50 p-6 space-y-3 text-zinc-300">
                        <p>📍 {{ $address }}</p>
                        <p>📞 {{ $settings->primary_phone }}</p>
                        <a href="{{ route('home', ['lang' => $language->code]) }}#contact" class="inline-flex px-5 py-3 rounded-lg bg-yellow-400 text-zinc-950 font-semibold hover:bg-yellow-300">{{ $isArabic ? 'تواصل معنا' : 'Contact us' }}</a>
                    </div>
                </div>
            </section>

            @if ($relatedWorks->isNotEmpty())
                <section class="mt-16 pt-10 border-t border-zinc-800">
                    <h2 class="text-2xl font-bold mb-6">{{ $isArabic ? 'أعمال مشابهة' : 'Related works' }}</h2>
                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($relatedWorks as $relatedWork)
                            @php($relatedTranslation = $relatedWork->translations->first())
                            <a href="{{ route('works.show', ['work' => $relatedWork->slug, 'lang' => $language->code]) }}" class="block rounded-3xl border border-zinc-800 bg-zinc-900/50 overflow-hidden hover:border-yellow-400 transition">
                                <img src="{{ $relatedWork->galleryImages()[0] ?? 'https://placehold.co/800x600/18181b/facc15?text=Work' }}" alt="{{ $relatedTranslation?->title }}" class="w-full h-52 object-cover">
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold">{{ $relatedTranslation?->title }}</h3>
                                    <p class="mt-2 text-zinc-400 text-sm">{{ $relatedTranslation?->short_description }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </body>
</html>
