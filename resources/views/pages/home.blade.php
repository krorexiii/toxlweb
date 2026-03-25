<!DOCTYPE html>
<html lang="{{ $language->code }}" dir="{{ $language->code === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ data_get($page->meta, 'title', $settings->site_name) }} | {{ $settings->site_name }}</title>
        <meta name="description" content="{{ data_get($page->meta, 'description', $settings->site_tagline) }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            html { scroll-behavior: smooth; }
        </style>
    </head>
    <body class="bg-zinc-950 text-zinc-100 antialiased">
        @php
            $about = $sections->get('about');
            $aboutTranslation = $about?->translations->first();
            $productsSection = $sections->get('products');
            $productsTranslation = $productsSection?->translations->first();
            $worksSection = $sections->get('works');
            $worksTranslation = $worksSection?->translations->first();
            $contactSection = $sections->get('contact');
            $contactTranslation = $contactSection?->translations->first();
            $address = data_get($localizedSettings, 'address', $settings->address);
            $tagline = data_get($localizedSettings, 'tagline', $settings->site_tagline);
            $footer = data_get($localizedSettings, 'footer', 'All rights reserved.');
            $contactNote = data_get($localizedSettings, 'contact_note');
            $isArabic = $language->code === 'ar';
        @endphp

        <div class="bg-yellow-400 text-zinc-950 border-b border-yellow-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap items-center justify-between gap-3 text-sm font-medium">
                <div class="flex flex-wrap items-center gap-4">
                    <p>📞 {{ $settings->primary_phone }}</p>
                    <p>📍 {{ $address }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @foreach ($languages as $navLanguage)
                        <a href="{{ route('home', ['lang' => $navLanguage->code]) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $navLanguage->is($language) ? 'bg-zinc-950 text-yellow-300' : 'bg-yellow-100/70 text-zinc-900' }} hover:bg-zinc-900 hover:text-yellow-300 transition">
                            {{ strtoupper($navLanguage->code) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <header class="sticky top-0 z-50 backdrop-blur bg-zinc-950/80 border-b border-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                <a href="#home" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-yellow-400 text-zinc-900 font-black grid place-items-center overflow-hidden">
                        @if ($settings->logo_path)
                            <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="{{ $settings->site_name }} logo" class="w-full h-full object-cover">
                        @else
                            <span>{{ str($settings->site_name)->substr(0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-lg leading-none">{{ $settings->site_name }}</p>
                        <p class="text-xs text-zinc-400">{{ $tagline }}</p>
                    </div>
                </a>

                <nav class="hidden md:flex items-center gap-6 text-sm text-zinc-300">
                    <a href="#home" class="hover:text-yellow-400">{{ $isArabic ? 'الرئيسية' : 'Home' }}</a>
                    <a href="#products" class="hover:text-yellow-400">{{ $productsTranslation?->title ?? ($isArabic ? 'المنتجات' : 'Products') }}</a>
                    <a href="#works" class="hover:text-yellow-400">{{ $worksTranslation?->title ?? ($isArabic ? 'الأعمال' : 'Works') }}</a>
                    <a href="#contact" class="hover:text-yellow-400">{{ $contactTranslation?->title ?? ($isArabic ? 'تواصل معنا' : 'Contact us') }}</a>
                </nav>
            </div>
        </header>

        <main>
            <section id="home" class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-yellow-500/10 via-transparent to-transparent"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 relative">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-yellow-500/30 bg-yellow-500/10 text-yellow-300 text-xs">
                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                        <span>{{ $aboutTranslation?->subtitle }}</span>
                    </span>
                    <h1 class="mt-6 text-4xl md:text-6xl font-extrabold leading-tight max-w-4xl">{{ $aboutTranslation?->title }}</h1>
                    <p class="mt-6 text-zinc-300 max-w-3xl text-lg">{{ $aboutTranslation?->body }}</p>

                    <div class="mt-8 grid sm:grid-cols-3 gap-4 max-w-4xl">
                        @foreach (data_get($about?->data, 'stats', []) as $stat)
                            <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-4">
                                <p class="text-yellow-400 text-sm">
                                    {{ match(data_get($stat, 'label_key')) {
                                        'products' => $isArabic ? 'المنتجات' : 'Products',
                                        'support' => $isArabic ? 'الدعم' : 'Support',
                                        'quality' => $isArabic ? 'الجودة' : 'Quality',
                                        default => data_get($stat, 'label_key'),
                                    } }}
                                </p>
                                <p class="mt-2 text-2xl font-bold">{{ data_get($stat, 'value') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        @if ($aboutTranslation?->cta_primary_label)
                            <a href="{{ $aboutTranslation->cta_primary_url }}" class="px-5 py-3 rounded-lg bg-yellow-400 text-zinc-900 font-semibold hover:bg-yellow-300">{{ $aboutTranslation->cta_primary_label }}</a>
                        @endif
                        @if ($aboutTranslation?->cta_secondary_label)
                            <a href="{{ $aboutTranslation->cta_secondary_url }}" class="px-5 py-3 rounded-lg border border-zinc-700 hover:border-yellow-400">{{ $aboutTranslation->cta_secondary_label }}</a>
                        @endif
                    </div>
                </div>
            </section>

            <section class="py-8 border-t border-zinc-900 bg-zinc-900/40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-4">
                    @foreach (($aboutTranslation?->items ?? []) as $item)
                        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-5 hover:border-yellow-400 transition">
                            <p class="text-yellow-400 font-semibold">{{ $item }}</p>
                            <p class="mt-2 text-zinc-300 text-sm">{{ $isArabic ? 'يمكن تعديل هذا المحتوى بالكامل من قاعدة البيانات.' : 'This content is fully manageable from the database.' }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section id="products" class="py-16 border-t border-zinc-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-10 max-w-3xl">
                        <h2 class="text-3xl font-bold">{{ $productsTranslation?->title }}</h2>
                        <p class="text-zinc-400 mt-2">{{ $productsTranslation?->body }}</p>
                    </div>

                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                            @php($translation = $product->translations->first())
                            <a href="{{ route('products.show', ['product' => $product->slug, 'lang' => $language->code]) }}" class="group rounded-3xl border border-zinc-800 bg-zinc-900/50 overflow-hidden flex flex-col hover:border-yellow-400 transition">
                                <img src="{{ $product->galleryImages()[0] ?? 'https://placehold.co/800x600/18181b/facc15?text=Product' }}" alt="{{ $translation?->name }}" class="w-full h-56 object-cover group-hover:scale-[1.02] transition duration-300">

                                <div class="p-6 flex flex-col grow">
                                    <div class="w-14 h-14 rounded-2xl bg-yellow-400/10 text-yellow-300 grid place-items-center text-2xl">⚡</div>
                                    <h3 class="mt-5 text-xl font-semibold">{{ $translation?->name }}</h3>
                                    <p class="mt-3 text-zinc-400">{{ $translation?->short_description }}</p>

                                    <ul class="mt-5 space-y-2 text-sm text-zinc-300 {{ $isArabic ? 'pr-5' : 'pl-5' }} list-disc">
                                        @foreach (($translation?->features ?? []) as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                    </ul>

                                    @if ($product->specifications)
                                        <div class="mt-6 rounded-2xl border border-zinc-800 overflow-hidden">
                                            <table class="w-full text-sm">
                                                <tbody class="divide-y divide-zinc-800 text-zinc-300">
                                                    @foreach ($product->specifications as $spec)
                                                        <tr>
                                                            <td class="p-3 font-medium text-zinc-100">{{ data_get($spec, 'label') }}</td>
                                                            <td class="p-3">{{ data_get($spec, 'value') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif

                                    <div class="mt-6 inline-flex items-center gap-2 text-yellow-300 text-sm font-semibold">
                                        <span>{{ $isArabic ? 'عرض التفاصيل' : 'View details' }}</span>
                                        <span>→</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="works" class="py-16 border-t border-zinc-900 bg-zinc-900/30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-10 max-w-3xl">
                        <h2 class="text-3xl font-bold">{{ $worksTranslation?->title }}</h2>
                        <p class="text-zinc-400 mt-2">{{ $worksTranslation?->body }}</p>
                    </div>

                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($works as $work)
                            @php($translation = $work->translations->first())
                            <a href="{{ route('works.show', ['work' => $work->slug, 'lang' => $language->code]) }}" class="group relative min-h-[420px] overflow-hidden rounded-3xl border border-zinc-800 bg-zinc-900 hover:border-yellow-400 transition block">
                                <img
                                    src="{{ $work->galleryImages()[0] ?? 'https://placehold.co/1200x900/18181b/facc15?text=Work' }}"
                                    alt="{{ $translation?->title }}"
                                    class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                >

                                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/60 to-zinc-950/10"></div>

                                <div class="relative flex h-full flex-col justify-end p-6 lg:p-7">
                                    <div class="mb-4 flex flex-wrap items-center gap-3 text-xs">
                                        <span class="rounded-full bg-yellow-400/90 px-3 py-1 font-semibold text-zinc-950">{{ $work->location }}</span>
                                        <span class="rounded-full border border-white/20 bg-black/20 px-3 py-1 text-zinc-100">{{ $work->client_name }}</span>
                                    </div>

                                    <h3 class="text-2xl font-bold text-white">{{ $translation?->title }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-zinc-200">{{ $translation?->description }}</p>

                                    @if ($translation?->highlights)
                                        <div class="mt-5 flex flex-wrap gap-2">
                                            @foreach ($translation->highlights as $highlight)
                                                <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs text-white backdrop-blur-sm">{{ $highlight }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-yellow-300">
                                        <span>{{ $isArabic ? 'عرض تفاصيل العمل' : 'View work details' }}</span>
                                        <span>→</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="contact" class="py-16 border-t border-zinc-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-10 max-w-3xl">
                        <h2 class="text-3xl font-bold">{{ $contactTranslation?->title }}</h2>
                        <p class="text-zinc-400 mt-3">{{ $contactTranslation?->body }}</p>
                    </div>

                    <div class="grid lg:grid-cols-5 gap-8 items-start">
                        <div class="lg:col-span-2 space-y-4">
                            <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-6">
                                <p class="text-zinc-400 text-sm">{{ $isArabic ? 'واتساب' : 'WhatsApp' }}</p>
                                <p class="mt-2 text-2xl font-bold">{{ $settings->primary_phone }}</p>
                                @if ($contactTranslation?->cta_primary_label)
                                    <a href="{{ $contactTranslation->cta_primary_url }}" target="_blank" rel="noreferrer" class="mt-5 inline-flex items-center justify-center px-5 py-3 rounded-lg bg-yellow-400 text-zinc-900 font-semibold hover:bg-yellow-300">{{ $contactTranslation->cta_primary_label }}</a>
                                @endif
                            </div>

                            <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-6 space-y-3 text-zinc-300 text-sm">
                                <p>📍 {{ $address }}</p>
                                <p>📞 {{ $settings->primary_phone }}</p>
                                @if ($settings->primary_email)
                                    <p>✉️ {{ $settings->primary_email }}</p>
                                @endif
                                @if ($contactNote)
                                    <p>🕒 {{ $contactNote }}</p>
                                @endif
                            </div>

                            @if ($settings->social_links)
                                <div class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-6">
                                    <p class="text-zinc-400 text-sm mb-4">{{ $isArabic ? 'مواقع التواصل' : 'Social links' }}</p>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($settings->social_links as $social)
                                            <a href="{{ data_get($social, 'url') }}" target="_blank" rel="noreferrer" class="px-4 py-2 rounded-full border border-zinc-700 hover:border-yellow-400 hover:text-yellow-300 transition text-sm">
                                                {{ data_get($social, 'label') }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="lg:col-span-3 rounded-3xl overflow-hidden border border-zinc-800 bg-zinc-900/50">
                            <div class="p-5 border-b border-zinc-800">
                                <h3 class="text-xl font-semibold">{{ $isArabic ? 'موقعنا على الخريطة' : 'Our location on the map' }}</h3>
                                <p class="mt-2 text-zinc-400 text-sm">{{ $address }}</p>
                            </div>
                            <iframe
                                title="{{ $settings->site_name }} map"
                                class="w-full h-[420px]"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                src="{{ $settings->map_embed_url }}">
                            </iframe>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-zinc-800 py-6 text-center text-sm text-zinc-500">
            © {{ now()->year }} {{ $settings->site_name }}. {{ $footer }}
        </footer>
    </body>
</html>
