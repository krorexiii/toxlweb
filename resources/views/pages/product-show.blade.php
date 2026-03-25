<!DOCTYPE html>
<html lang="{{ $language->code }}" dir="{{ $language->code === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $translation?->name }} | {{ $settings->site_name }}</title>
        <meta name="description" content="{{ $translation?->short_description }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 text-zinc-100 antialiased">
        @php
            $isArabic = $language->code === 'ar';
            $gallery = $product->galleryImages();
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
                        <a href="{{ route('products.show', ['product' => $product->slug, 'lang' => $navLanguage->code]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $navLanguage->is($language) ? 'bg-yellow-400 text-zinc-950' : 'bg-zinc-900 text-zinc-200 border border-zinc-800' }}">
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
                <a href="{{ route('home', ['lang' => $language->code]) }}#products" class="hover:text-yellow-400">{{ $isArabic ? 'المنتجات' : 'Products' }}</a>
                <span>/</span>
                <span class="text-zinc-100">{{ $translation?->name }}</span>
            </div>

            <section class="grid lg:grid-cols-2 gap-10 items-start">
                <div class="space-y-4">
                    <div class="rounded-3xl overflow-hidden border border-zinc-800 bg-zinc-900/50">
                        <img src="{{ $gallery[0] ?? 'https://placehold.co/1200x900/18181b/facc15?text=Product' }}" alt="{{ $translation?->name }}" class="w-full h-[420px] md:h-[520px] object-cover">
                    </div>

                    @if (count($gallery) > 1)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($gallery as $image)
                                <div class="rounded-2xl overflow-hidden border border-zinc-800 bg-zinc-900/50">
                                    <img src="{{ $image }}" alt="{{ $translation?->name }} gallery image" class="w-full h-32 object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-400/10 text-yellow-300 border border-yellow-400/20 text-xs">
                        {{ $product->sku }}
                    </span>
                    <h1 class="mt-5 text-4xl font-extrabold">{{ $translation?->name }}</h1>
                    <p class="mt-4 text-lg text-zinc-300">{{ $translation?->short_description }}</p>

                    <div class="mt-6 rounded-3xl border border-zinc-800 bg-zinc-900/50 p-6">
                        <h2 class="text-xl font-semibold mb-4">{{ $isArabic ? 'وصف المنتج' : 'Product description' }}</h2>
                        <p class="text-zinc-300 leading-8">{{ $translation?->description }}</p>
                    </div>

                    @if ($translation?->features)
                        <div class="mt-6 rounded-3xl border border-zinc-800 bg-zinc-900/50 p-6">
                            <h2 class="text-xl font-semibold mb-4">{{ $isArabic ? 'المميزات' : 'Features' }}</h2>
                            <ul class="space-y-3 text-zinc-300 {{ $isArabic ? 'pr-5' : 'pl-5' }} list-disc">
                                @foreach ($translation->features as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($product->specifications)
                        <div class="mt-6 rounded-3xl border border-zinc-800 bg-zinc-900/50 overflow-hidden">
                            <div class="p-6 border-b border-zinc-800">
                                <h2 class="text-xl font-semibold">{{ $isArabic ? 'المواصفات' : 'Specifications' }}</h2>
                            </div>
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-zinc-800 text-zinc-300">
                                    @foreach ($product->specifications as $spec)
                                        <tr>
                                            <td class="p-4 font-medium text-zinc-100">{{ data_get($spec, 'label') }}</td>
                                            <td class="p-4">{{ data_get($spec, 'value') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-6 rounded-3xl border border-zinc-800 bg-zinc-900/50 p-6 space-y-3 text-zinc-300">
                        <p>📞 {{ $settings->primary_phone }}</p>
                        <p>📍 {{ $address }}</p>
                        <a href="{{ route('home', ['lang' => $language->code]) }}#contact" class="inline-flex px-5 py-3 rounded-lg bg-yellow-400 text-zinc-950 font-semibold hover:bg-yellow-300">{{ $isArabic ? 'اطلب الآن' : 'Request now' }}</a>
                    </div>
                </div>
            </section>

            @if ($relatedProducts->isNotEmpty())
                <section class="mt-16 pt-10 border-t border-zinc-800">
                    <h2 class="text-2xl font-bold mb-6">{{ $isArabic ? 'منتجات مشابهة' : 'Related products' }}</h2>
                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($relatedProducts as $relatedProduct)
                            @php($relatedTranslation = $relatedProduct->translations->first())
                            <a href="{{ route('products.show', ['product' => $relatedProduct->slug, 'lang' => $language->code]) }}" class="block rounded-3xl border border-zinc-800 bg-zinc-900/50 overflow-hidden hover:border-yellow-400 transition">
                                <img src="{{ $relatedProduct->galleryImages()[0] ?? 'https://placehold.co/800x600/18181b/facc15?text=Product' }}" alt="{{ $relatedTranslation?->name }}" class="w-full h-52 object-cover">
                                <div class="p-5">
                                    <h3 class="text-lg font-semibold">{{ $relatedTranslation?->name }}</h3>
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
