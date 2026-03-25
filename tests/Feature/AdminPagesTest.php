<?php

use App\Models\User;
use Database\Seeders\HomepageSeeder;

beforeEach(function () {
    $this->seed(HomepageSeeder::class);
});

it('allows an authenticated user to view admin management pages', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('admin.site-settings'))
        ->assertOk()
        ->assertSee('إعدادات الموقع')
        ->assertSee('اسم الموقع');

    $this->get(route('admin.languages'))
        ->assertOk()
        ->assertSee('إدارة اللغات')
        ->assertSee('العربية')
        ->assertSee('لغة جديدة')
        ->assertSee('حفظ اللغة');

    $this->get(route('admin.products'))
        ->assertOk()
        ->assertSee('إدارة المنتجات')
        ->assertSee('قواطع DC TOXLS3-63DC')
        ->assertSee('منتج جديد')
        ->assertSee('حفظ المنتج')
        ->assertSee('رفع الصورة الرئيسية')
        ->assertSee('رفع صور المعرض');

    $this->get(route('admin.works'))
        ->assertOk()
        ->assertSee('إدارة الأعمال')
        ->assertSee('تجهيز لوحة توزيع صناعية')
        ->assertSee('عمل جديد')
        ->assertSee('حفظ العمل')
        ->assertSee('رفع الصورة الرئيسية')
        ->assertSee('رفع صور المعرض');
});
