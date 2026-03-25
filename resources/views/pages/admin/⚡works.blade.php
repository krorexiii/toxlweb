<?php

use App\Models\Language;
use App\Models\Work;
use App\Models\WorkTranslation;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Works')] class extends Component {
    use WithFileUploads;

    public ?int $editingWorkId = null;

    #[Validate('nullable|string|max:1000', onUpdate: false)]
    public string $image_path = '';

    #[Validate('nullable|image|max:5120', onUpdate: false)]
    public $image_upload = null;

    #[Validate('nullable|string', onUpdate: false)]
    public string $gallery_text = '';

    #[Validate(['gallery_uploads.*' => 'nullable|image|max:5120'], onUpdate: false)]
    public array $gallery_uploads = [];

    #[Validate('nullable|string|max:255', onUpdate: false)]
    public string $client_name = '';

    #[Validate('nullable|string|max:255', onUpdate: false)]
    public string $location = '';

    #[Validate('nullable|date', onUpdate: false)]
    public string $completed_at = '';

    #[Validate('nullable|integer|min:0', onUpdate: false)]
    public int|string $sort_order = 0;

    #[Validate('nullable|string', onUpdate: false)]
    public string $metrics_text = '';

    public bool $is_active = true;
    public bool $is_featured = false;

    #[Validate('required|string|max:255', onUpdate: false)]
    public string $title_ar = '';

    #[Validate('nullable|string|max:255', onUpdate: false)]
    public string $short_description_ar = '';

    #[Validate('nullable|string', onUpdate: false)]
    public string $description_ar = '';

    #[Validate('nullable|string', onUpdate: false)]
    public string $highlights_ar_text = '';

    #[Validate('required|string|max:255', onUpdate: false)]
    public string $title_en = '';

    #[Validate('nullable|string|max:255', onUpdate: false)]
    public string $short_description_en = '';

    #[Validate('nullable|string', onUpdate: false)]
    public string $description_en = '';

    #[Validate('nullable|string', onUpdate: false)]
    public string $highlights_en_text = '';

    #[Computed]
    public function works()
    {
        return Work::query()->with('translations.language')->orderBy('sort_order')->orderBy('id')->get();
    }

    public function mount(): void
    {
        $this->resetForm();
    }

    public function createNew(): void
    {
        $this->resetForm();
    }

    public function edit(int $workId): void
    {
        $work = Work::query()->with('translations.language')->findOrFail($workId);

        $this->editingWorkId = $work->id;
        $this->image_path = $work->image_path ?? '';
    $this->image_upload = null;
        $this->gallery_text = implode(PHP_EOL, $work->gallery ?? []);
    $this->gallery_uploads = [];
        $this->client_name = $work->client_name ?? '';
        $this->location = $work->location ?? '';
        $this->completed_at = $work->completed_at?->format('Y-m-d') ?? '';
        $this->sort_order = $work->sort_order;
        $this->metrics_text = implode(PHP_EOL, collect($work->metrics ?? [])->map(fn ($metric) => data_get($metric, 'label').': '.data_get($metric, 'value'))->all());
        $this->is_active = $work->is_active;
        $this->is_featured = $work->is_featured;

        $arabic = $work->translations->firstWhere('language.code', 'ar');
        $english = $work->translations->firstWhere('language.code', 'en');

        $this->title_ar = $arabic?->title ?? '';
        $this->short_description_ar = $arabic?->short_description ?? '';
        $this->description_ar = $arabic?->description ?? '';
        $this->highlights_ar_text = implode(PHP_EOL, $arabic?->highlights ?? []);

        $this->title_en = $english?->title ?? '';
        $this->short_description_en = $english?->short_description ?? '';
        $this->description_en = $english?->description ?? '';
        $this->highlights_en_text = implode(PHP_EOL, $english?->highlights ?? []);
    }

    public function save(): void
    {
        $validated = $this->validate();

        $storedImagePath = $this->storeUploadedFile($this->image_upload, 'works');
        $galleryPaths = array_merge(
            $this->linesToArray($validated['gallery_text']),
            $this->storeUploadedFiles($this->gallery_uploads, 'works/gallery')
        );

        $work = Work::query()->updateOrCreate(
            ['id' => $this->editingWorkId],
            [
                'slug' => $this->existingSlug() ?? Str::slug($validated['title_en'] ?: $validated['title_ar']).'-'.Str::lower(Str::random(4)),
                'image_path' => $storedImagePath ?: ($validated['image_path'] ?: null),
                'gallery' => array_values(array_unique(array_filter($galleryPaths))),
                'client_name' => $validated['client_name'] ?: null,
                'location' => $validated['location'] ?: null,
                'completed_at' => $validated['completed_at'] ?: null,
                'sort_order' => (int) $validated['sort_order'],
                'is_active' => $this->is_active,
                'is_featured' => $this->is_featured,
                'metrics' => $this->keyValueLinesToArray($validated['metrics_text']),
            ]
        );

        $this->syncTranslation($work, 'ar', [
            'title' => $validated['title_ar'],
            'short_description' => $validated['short_description_ar'] ?: null,
            'description' => $validated['description_ar'] ?: null,
            'highlights' => $this->linesToArray($validated['highlights_ar_text']),
        ]);

        $this->syncTranslation($work, 'en', [
            'title' => $validated['title_en'],
            'short_description' => $validated['short_description_en'] ?: null,
            'description' => $validated['description_en'] ?: null,
            'highlights' => $this->linesToArray($validated['highlights_en_text']),
        ]);

        $this->editingWorkId = $work->id;
        $this->image_path = $work->image_path ?? '';
        $this->gallery_text = implode(PHP_EOL, $work->gallery ?? []);
        $this->image_upload = null;
        $this->gallery_uploads = [];
        unset($this->works);
        $this->dispatch('work-saved');
    }

    public function toggleStatus(int $workId): void
    {
        $work = Work::query()->findOrFail($workId);
        $work->update(['is_active' => ! $work->is_active]);

        if ($this->editingWorkId === $workId) {
            $this->is_active = $work->is_active;
        }

        unset($this->works);
    }

    private function existingSlug(): ?string
    {
        if (! $this->editingWorkId) {
            return null;
        }

        return Work::query()->find($this->editingWorkId)?->slug;
    }

    private function syncTranslation(Work $work, string $code, array $data): void
    {
        $languageId = Language::query()->where('code', $code)->value('id');

        if (! $languageId) {
            return;
        }

        WorkTranslation::query()->updateOrCreate(
            [
                'work_id' => $work->id,
                'language_id' => $languageId,
            ],
            $data
        );
    }

    private function linesToArray(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function keyValueLinesToArray(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$label, $itemValue] = array_pad(explode(':', $line, 2), 2, '');

                return [
                    'label' => trim($label),
                    'value' => trim($itemValue),
                ];
            })
            ->filter(fn ($item) => $item['label'] !== '' || $item['value'] !== '')
            ->values()
            ->all();
    }

    private function storeUploadedFile(mixed $file, string $directory): ?string
    {
        if (! $file instanceof TemporaryUploadedFile) {
            return null;
        }

        return $file->store($directory, 'public');
    }

    private function storeUploadedFiles(array $files, string $directory): array
    {
        return collect($files)
            ->map(fn ($file) => $this->storeUploadedFile($file, $directory))
            ->filter()
            ->values()
            ->all();
    }

    private function resetForm(): void
    {
        $this->editingWorkId = null;
        $this->image_path = '';
        $this->image_upload = null;
        $this->gallery_text = '';
        $this->gallery_uploads = [];
        $this->client_name = '';
        $this->location = '';
        $this->completed_at = '';
        $this->sort_order = 0;
        $this->metrics_text = '';
        $this->is_active = true;
        $this->is_featured = false;
        $this->title_ar = '';
        $this->short_description_ar = '';
        $this->description_ar = '';
        $this->highlights_ar_text = '';
        $this->title_en = '';
        $this->short_description_en = '';
        $this->description_en = '';
        $this->highlights_en_text = '';
    }
}; ?>

<section class="w-full">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">إدارة الأعمال</flux:heading>
            <flux:subheading>إضافة وتعديل الأعمال الحالية مع الترجمات والصور والحالة داخل الداشبورد.</flux:subheading>
        </div>
        <div class="flex items-center gap-3">
            <flux:badge color="fuchsia">{{ $this->works->count() }} عمل</flux:badge>
            <flux:button variant="primary" wire:click="createNew">عمل جديد</flux:button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-5">
        <div class="space-y-4 xl:col-span-3">
            @forelse ($this->works as $work)
                @php($translation = $work->translations->first())
                <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="flex flex-col gap-4 p-5 lg:flex-row">
                        <img src="{{ $work->galleryImages()[0] ?? 'https://placehold.co/800x600/18181b/facc15?text=Work' }}" alt="{{ $translation?->title }}" class="h-48 w-full rounded-2xl object-cover lg:w-52">

                        <div class="flex-1">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="mb-3 flex flex-wrap gap-2 text-xs">
                                        <span class="rounded-full bg-zinc-100 px-3 py-1 dark:bg-zinc-800">{{ $work->location ?: '—' }}</span>
                                        <flux:badge :color="$work->is_featured ? 'amber' : 'zinc'">{{ $work->is_featured ? 'مميز' : 'عادي' }}</flux:badge>
                                        <flux:badge :color="$work->is_active ? 'lime' : 'zinc'">{{ $work->is_active ? 'مفعّل' : 'معطّل' }}</flux:badge>
                                    </div>
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $translation?->title ?? $work->slug }}</h3>
                                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">{{ $translation?->short_description }}</p>
                                </div>

                                <div class="flex gap-2">
                                    <flux:button size="sm" variant="ghost" wire:click="edit({{ $work->id }})">تعديل</flux:button>
                                    <flux:button size="sm" variant="ghost" wire:click="toggleStatus({{ $work->id }})">{{ $work->is_active ? 'تعطيل' : 'تفعيل' }}</flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-zinc-500">لا توجد أعمال حاليًا.</p>
            @endforelse
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 xl:col-span-2">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $editingWorkId ? 'تعديل العمل' : 'إضافة عمل' }}</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">النسخة الحالية تدعم الصور والترجمة والمقاييس الأساسية.</p>

            <form wire:submit="save" class="mt-6 space-y-5">
                <div class="space-y-3 rounded-2xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <flux:input wire:model="image_upload" label="رفع الصورة الرئيسية" type="file" accept="image/*" />
                    @if ($image_path)
                        <img src="{{ $this->works->firstWhere('id', $editingWorkId)?->resolveImageUrl($image_path) ?? (str_starts_with($image_path, 'http') ? $image_path : asset('storage/'.$image_path)) }}" alt="Preview" class="h-32 w-full rounded-2xl object-cover">
                    @endif
                    <flux:input wire:model="image_path" label="أو ضع رابط/مسار الصورة الرئيسية" type="text" />
                </div>

                <div class="space-y-3 rounded-2xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <flux:input wire:model="gallery_uploads" label="رفع صور المعرض" type="file" accept="image/*" multiple />
                    <flux:textarea wire:model="gallery_text" label="أو روابط/مسارات صور المعرض" rows="4" placeholder="ضع كل رابط أو مسار في سطر مستقل" />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input wire:model="client_name" label="اسم العميل" type="text" />
                    <flux:input wire:model="location" label="الموقع" type="text" />
                    <flux:input wire:model="completed_at" label="تاريخ الإنجاز" type="date" />
                    <flux:input wire:model="sort_order" label="الترتيب" type="number" min="0" />
                </div>

                <flux:textarea wire:model="metrics_text" label="المؤشرات" rows="4" placeholder="Panels: 24&#10;Protection points: 60+" />

                <div class="grid gap-4 md:grid-cols-2">
                    <flux:checkbox wire:model="is_active" label="العمل مفعّل" />
                    <flux:checkbox wire:model="is_featured" label="العمل مميز" />
                </div>

                <div class="rounded-2xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <flux:heading size="lg">الترجمة العربية</flux:heading>
                    <div class="mt-4 space-y-4">
                        <flux:input wire:model="title_ar" label="العنوان بالعربية" type="text" required />
                        <flux:input wire:model="short_description_ar" label="الوصف المختصر بالعربية" type="text" />
                        <flux:textarea wire:model="description_ar" label="التفاصيل بالعربية" rows="4" />
                        <flux:textarea wire:model="highlights_ar_text" label="أبرز النقاط بالعربية" rows="4" placeholder="ضع كل نقطة في سطر مستقل" />
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200 p-4 dark:border-zinc-700">
                    <flux:heading size="lg">English translation</flux:heading>
                    <div class="mt-4 space-y-4">
                        <flux:input wire:model="title_en" label="Title in English" type="text" required />
                        <flux:input wire:model="short_description_en" label="Short description in English" type="text" />
                        <flux:textarea wire:model="description_en" label="Details in English" rows="4" />
                        <flux:textarea wire:model="highlights_en_text" label="Highlights in English" rows="4" placeholder="One highlight per line" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <flux:button variant="primary" type="submit">حفظ العمل</flux:button>
                    <flux:button variant="ghost" type="button" wire:click="createNew">تفريغ النموذج</flux:button>
                    <x-action-message on="work-saved">تم حفظ العمل.</x-action-message>
                </div>
            </form>
        </div>
    </div>
</section>
