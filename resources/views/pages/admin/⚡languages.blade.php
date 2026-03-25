<?php

use App\Models\Language;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Languages')] class extends Component {
    public ?int $editingLanguageId = null;

    #[Validate('required|string|max:255', onUpdate: false)]
    public string $name = '';

    #[Validate('required|string|max:255', onUpdate: false)]
    public string $native_name = '';

    #[Validate('required|string|size:2|alpha', onUpdate: false)]
    public string $code = '';

    #[Validate('nullable|integer|min:0', onUpdate: false)]
    public int|string $sort_order = 0;

    public bool $is_active = true;
    public bool $is_default = false;

    #[Computed]
    public function languages()
    {
        return Language::query()->orderBy('sort_order')->orderBy('id')->get();
    }

    public function mount(): void
    {
        $this->resetForm();
    }

    public function createNew(): void
    {
        $this->resetForm();
    }

    public function edit(int $languageId): void
    {
        $language = Language::query()->findOrFail($languageId);

        $this->editingLanguageId = $language->id;
        $this->name = $language->name;
        $this->native_name = $language->native_name;
        $this->code = $language->code;
        $this->sort_order = $language->sort_order;
        $this->is_active = $language->is_active;
        $this->is_default = $language->is_default;
    }

    public function save(): void
    {
        $validated = $this->validate();

        validator(
            ['code' => strtolower($validated['code'])],
            ['code' => 'unique:languages,code,'.($this->editingLanguageId ?: 'NULL').',id']
        )->validate();

        if ($this->is_default) {
            Language::query()->update(['is_default' => false]);
            $this->is_active = true;
        }

        $language = Language::query()->updateOrCreate(
            ['id' => $this->editingLanguageId],
            [
                'name' => $validated['name'],
                'native_name' => $validated['native_name'],
                'code' => strtolower($validated['code']),
                'sort_order' => (int) $validated['sort_order'],
                'is_active' => $this->is_active,
                'is_default' => $this->is_default,
            ]
        );

        if (! $language->is_default && ! Language::query()->where('is_default', true)->exists()) {
            $language->update(['is_default' => true, 'is_active' => true]);
            $this->is_default = true;
            $this->is_active = true;
        }

        $this->editingLanguageId = $language->id;
        unset($this->languages);
        $this->dispatch('language-saved');
    }

    public function toggleStatus(int $languageId): void
    {
        $language = Language::query()->findOrFail($languageId);

        if ($language->is_default) {
            return;
        }

        $language->update(['is_active' => ! $language->is_active]);

        if ($this->editingLanguageId === $languageId) {
            $this->is_active = $language->is_active;
        }

        unset($this->languages);
    }

    public function makeDefault(int $languageId): void
    {
        Language::query()->update(['is_default' => false]);

        $language = Language::query()->findOrFail($languageId);
        $language->update([
            'is_default' => true,
            'is_active' => true,
        ]);

        if ($this->editingLanguageId === $languageId) {
            $this->is_default = true;
            $this->is_active = true;
        }

        unset($this->languages);
    }

    private function resetForm(): void
    {
        $this->editingLanguageId = null;
        $this->name = '';
        $this->native_name = '';
        $this->code = '';
        $this->sort_order = 0;
        $this->is_active = true;
        $this->is_default = false;
    }
}; ?>

<section class="w-full">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <flux:heading size="xl">إدارة اللغات</flux:heading>
            <flux:subheading>إضافة وتعديل اللغات وتحديد اللغة الافتراضية من داخل لوحة التحكم.</flux:subheading>
        </div>
        <div class="flex items-center gap-3">
            <flux:badge color="lime">{{ $this->languages->count() }} لغة</flux:badge>
            <flux:button variant="primary" wire:click="createNew">لغة جديدة</flux:button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-5">
        <div class="xl:col-span-3">
            <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/80">
                        <tr>
                            <th class="px-4 py-3 text-start">الاسم</th>
                            <th class="px-4 py-3 text-start">الكود</th>
                            <th class="px-4 py-3 text-start">الترتيب</th>
                            <th class="px-4 py-3 text-start">الحالة</th>
                            <th class="px-4 py-3 text-start">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse ($this->languages as $language)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-zinc-900 dark:text-white">{{ $language->native_name }}</div>
                                    <div class="text-xs text-zinc-500">{{ $language->name }}</div>
                                </td>
                                <td class="px-4 py-3">{{ strtoupper($language->code) }}</td>
                                <td class="px-4 py-3">{{ $language->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @if ($language->is_default)
                                            <flux:badge color="amber">افتراضية</flux:badge>
                                        @endif
                                        <flux:badge :color="$language->is_active ? 'lime' : 'zinc'">{{ $language->is_active ? 'مفعّلة' : 'معطّلة' }}</flux:badge>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <flux:button size="sm" variant="ghost" wire:click="edit({{ $language->id }})">تعديل</flux:button>
                                        <flux:button size="sm" variant="ghost" wire:click="makeDefault({{ $language->id }})">افتراضية</flux:button>
                                        @unless ($language->is_default)
                                            <flux:button size="sm" variant="ghost" wire:click="toggleStatus({{ $language->id }})">{{ $language->is_active ? 'تعطيل' : 'تفعيل' }}</flux:button>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-zinc-500">لا توجد لغات حاليًا.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900 xl:col-span-2">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $editingLanguageId ? 'تعديل اللغة' : 'إضافة لغة' }}</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">الأفضل استخدام كود من حرفين مثل ar و en.</p>

            <form wire:submit="save" class="mt-6 space-y-4">
                <flux:input wire:model="native_name" label="الاسم الظاهر" type="text" required />
                <flux:input wire:model="name" label="الاسم الداخلي" type="text" required />
                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input wire:model="code" label="الكود" type="text" maxlength="2" required />
                    <flux:input wire:model="sort_order" label="الترتيب" type="number" min="0" />
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <flux:checkbox wire:model="is_active" label="اللغة مفعّلة" />
                    <flux:checkbox wire:model="is_default" label="اللغة الافتراضية" />
                </div>

                <div class="flex items-center gap-3">
                    <flux:button variant="primary" type="submit">حفظ اللغة</flux:button>
                    <flux:button variant="ghost" type="button" wire:click="createNew">تفريغ النموذج</flux:button>
                    <x-action-message on="language-saved">تم حفظ اللغة.</x-action-message>
                </div>
            </form>
        </div>
    </div>
</section>
