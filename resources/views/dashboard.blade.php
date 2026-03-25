<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <section class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">لوحة التحكم</p>
                    <h1 class="mt-2 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ $setting?->site_name ?? 'Website Dashboard' }}
                    </h1>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-300">
                        إدارة اللغات، محتوى الصفحة الرئيسية، المنتجات، الأعمال، والهوية البصرية من مكان واحد.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-zinc-100 p-4 dark:bg-zinc-800">
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">الهاتف</p>
                        <p class="mt-2 text-sm font-semibold text-zinc-900 dark:text-white">{{ $setting?->primary_phone ?? '—' }}</p>
                    </div>
                    <div class="rounded-2xl bg-zinc-100 p-4 dark:bg-zinc-800">
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">البريد</p>
                        <p class="mt-2 text-sm font-semibold text-zinc-900 dark:text-white">{{ $setting?->primary_email ?? '—' }}</p>
                    </div>
                    <div class="rounded-2xl bg-zinc-100 p-4 dark:bg-zinc-800 col-span-2 sm:col-span-1">
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">العنوان</p>
                        <p class="mt-2 text-sm font-semibold text-zinc-900 dark:text-white">{{ $setting?->address ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">اللغات</p>
                <p class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['languages'] }}</p>
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">اللغات المفعلة داخل الموقع</p>
            </div>
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">المنتجات</p>
                <p class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['products'] }}</p>
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">منها {{ $stats['active_products'] }} منتج مفعّل</p>
            </div>
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">الأعمال</p>
                <p class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['works'] }}</p>
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">منها {{ $stats['featured_works'] }} عمل مميز</p>
            </div>
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">أقسام الموقع</p>
                <p class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['sections'] }}</p>
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">عبر {{ $stats['pages'] }} صفحة</p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 xl:col-span-2">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">المنتجات الأخيرة</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">أحدث ما تمت إضافته أو تجهيزه داخل النظام</p>
                    </div>
                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">{{ $latestProducts->count() }} عناصر</span>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-700">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 text-zinc-500 dark:bg-zinc-800/70 dark:text-zinc-400">
                            <tr>
                                <th class="px-4 py-3 text-start">المنتج</th>
                                <th class="px-4 py-3 text-start">SKU</th>
                                <th class="px-4 py-3 text-start">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @forelse ($latestProducts as $product)
                                @php($translation = $product->translations->first())
                                <tr class="bg-white dark:bg-zinc-900">
                                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $translation?->name ?? $product->slug }}</td>
                                    <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">{{ $product->sku ?: '—' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' }}">
                                            {{ $product->is_active ? 'مفعّل' : 'غير مفعّل' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">لا توجد منتجات بعد.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">اللغات المتاحة</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">إعدادات الترجمة الحالية للموقع</p>

                <div class="mt-6 space-y-3">
                    @forelse ($languages as $language)
                        <div class="rounded-2xl bg-zinc-50 p-4 dark:bg-zinc-800/70">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $language->native_name }}</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ strtoupper($language->code) }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2 justify-end">
                                    @if ($language->is_default)
                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-300">افتراضية</span>
                                    @endif

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $language->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' }}">
                                        {{ $language->is_active ? 'مفعّلة' : 'معطّلة' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">لا توجد لغات بعد.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">الأعمال الأخيرة</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">آخر الأعمال المضافة داخل الموقع</p>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    @forelse ($latestWorks as $work)
                        @php($translation = $work->translations->first())
                        <div class="rounded-2xl border border-zinc-200 p-4 dark:border-zinc-700">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-zinc-900 dark:text-white">{{ $translation?->title ?? $work->slug }}</p>
                                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ $work->location ?: '—' }} · {{ $work->client_name ?: '—' }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $work->is_featured ? 'bg-fuchsia-100 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' }}">
                                    {{ $work->is_featured ? 'مميز' : 'عادي' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">لا توجد أعمال بعد.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">الخطوات التالية</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">المرحلة الحالية من الداشبورد جاهزة كبداية لإدارة الموقع.</p>

                <div class="mt-6 grid gap-3">
                    <a href="{{ route('admin.site-settings') }}" class="block rounded-2xl bg-zinc-50 p-4 transition hover:bg-zinc-100 dark:bg-zinc-800/70 dark:hover:bg-zinc-800">
                        <p class="font-semibold text-zinc-900 dark:text-white">1. إدارة اللغات</p>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">ابدأ من إعدادات الموقع لتحديث الاسم والهاتف والبريد والروابط.</p>
                    </a>
                    <a href="{{ route('admin.languages') }}" class="block rounded-2xl bg-zinc-50 p-4 transition hover:bg-zinc-100 dark:bg-zinc-800/70 dark:hover:bg-zinc-800">
                        <p class="font-semibold text-zinc-900 dark:text-white">2. إعدادات الموقع</p>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">راجع اللغات الحالية وحدد اللغات التي ستدعمها المنصة.</p>
                    </a>
                    <a href="{{ route('admin.products') }}" class="block rounded-2xl bg-zinc-50 p-4 transition hover:bg-zinc-100 dark:bg-zinc-800/70 dark:hover:bg-zinc-800">
                        <p class="font-semibold text-zinc-900 dark:text-white">3. إدارة المنتجات والأعمال</p>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">استعرض المنتجات الحالية كبداية، ثم الأعمال من القائمة الجانبية.</p>
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-layouts::app>
