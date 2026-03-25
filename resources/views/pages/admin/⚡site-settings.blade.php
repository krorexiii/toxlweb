<?php

use App\Models\SiteSetting;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Site settings')] class extends Component {
    #[Validate('required|string|max:255', onUpdate: false)]
    public string $site_name = '';

    #[Validate('nullable|string|max:255', onUpdate: false)]
    public string $site_tagline = '';

    #[Validate('nullable|string|max:255', onUpdate: false)]
    public string $primary_phone = '';

    #[Validate('nullable|string|max:255', onUpdate: false)]
    public string $secondary_phone = '';

    #[Validate('nullable|email|max:255', onUpdate: false)]
    public string $primary_email = '';

    #[Validate('nullable|string|max:255', onUpdate: false)]
    public string $address = '';

    #[Validate('nullable|url|max:1000', onUpdate: false)]
    public string $map_embed_url = '';

    #[Validate('nullable|url|max:1000', onUpdate: false)]
    public string $facebook_url = '';

    #[Validate('nullable|url|max:1000', onUpdate: false)]
    public string $instagram_url = '';

    #[Validate('nullable|url|max:1000', onUpdate: false)]
    public string $whatsapp_url = '';

    public SiteSetting $setting;

    public function mount(): void
    {
        $this->setting = SiteSetting::query()->firstOrCreate(
            ['id' => 1],
            ['site_name' => 'My Website']
        );

        $this->site_name = $this->setting->site_name ?? '';
        $this->site_tagline = $this->setting->site_tagline ?? '';
        $this->primary_phone = $this->setting->primary_phone ?? '';
        $this->secondary_phone = $this->setting->secondary_phone ?? '';
        $this->primary_email = $this->setting->primary_email ?? '';
        $this->address = $this->setting->address ?? '';
        $this->map_embed_url = $this->setting->map_embed_url ?? '';
        $this->facebook_url = collect($this->setting->social_links)->firstWhere('label', 'Facebook')['url'] ?? '';
        $this->instagram_url = collect($this->setting->social_links)->firstWhere('label', 'Instagram')['url'] ?? '';
        $this->whatsapp_url = collect($this->setting->social_links)->firstWhere('label', 'WhatsApp')['url'] ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->setting->update([
            'site_name' => $validated['site_name'],
            'site_tagline' => $validated['site_tagline'] ?: null,
            'primary_phone' => $validated['primary_phone'] ?: null,
            'secondary_phone' => $validated['secondary_phone'] ?: null,
            'primary_email' => $validated['primary_email'] ?: null,
            'address' => $validated['address'] ?: null,
            'map_embed_url' => $validated['map_embed_url'] ?: null,
            'social_links' => array_values(array_filter([
                $validated['whatsapp_url'] ? ['label' => 'WhatsApp', 'url' => $validated['whatsapp_url']] : null,
                $validated['facebook_url'] ? ['label' => 'Facebook', 'url' => $validated['facebook_url']] : null,
                $validated['instagram_url'] ? ['label' => 'Instagram', 'url' => $validated['instagram_url']] : null,
            ])),
        ]);

        $this->dispatch('site-settings-saved');
    }
}; ?>

<section class="w-full">
    <div class="mb-6">
        <flux:heading size="xl">إعدادات الموقع</flux:heading>
        <flux:subheading>تحديث اسم الموقع، بيانات التواصل، والعنوان وروابط المنصات.</flux:subheading>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <flux:input wire:model="site_name" label="اسم الموقع" type="text" required />
            <flux:input wire:model="site_tagline" label="الشعار النصي" type="text" />
            <flux:input wire:model="primary_phone" label="الهاتف الأساسي" type="text" />
            <flux:input wire:model="secondary_phone" label="الهاتف الثانوي" type="text" />
            <flux:input wire:model="primary_email" label="البريد الإلكتروني" type="email" />
            <flux:input wire:model="address" label="العنوان" type="text" />
            <div class="lg:col-span-2">
                <flux:input wire:model="map_embed_url" label="رابط الخريطة المضمنة" type="url" />
            </div>
        </div>

        <div class="rounded-3xl border border-zinc-200 p-5 dark:border-zinc-700">
            <flux:heading size="lg">روابط التواصل</flux:heading>
            <div class="mt-4 grid gap-4 lg:grid-cols-3">
                <flux:input wire:model="whatsapp_url" label="WhatsApp" type="url" />
                <flux:input wire:model="facebook_url" label="Facebook" type="url" />
                <flux:input wire:model="instagram_url" label="Instagram" type="url" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <flux:button variant="primary" type="submit">حفظ التغييرات</flux:button>
            <x-action-message on="site-settings-saved">تم الحفظ بنجاح.</x-action-message>
        </div>
    </form>
</section>
