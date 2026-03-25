<?php

use App\Models\Language;
use App\Models\Page;
use App\Models\SiteSetting;
use Database\Seeders\HomepageSeeder;

beforeEach(function () {
    $this->seed(HomepageSeeder::class);
});

it('renders the homepage using seeded database content', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('TOXL');
    $response->assertSee('حلول كهربائية وطاقة شمسية موثوقة');
    $response->assertSee('المنتجات');
});

it('switches homepage language using query string', function () {
    $response = $this->get('/?lang=en');

    $response->assertOk();
    $response->assertSee('Reliable electrical and solar solutions');
    $response->assertSee('Products');
    $response->assertSee('Contact us');
});

it('shows product images and detail links on the homepage', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('https://images.unsplash.com/photo-1581092918056-0c4c3acd3789', false);
    $response->assertSee(route('products.show', ['product' => 'toxls3-63dc', 'lang' => 'ar']), false);
});

it('renders a full product details page', function () {
    $response = $this->get(route('products.show', ['product' => 'toxls3-63dc', 'lang' => 'ar']));

    $response->assertOk();
    $response->assertSee('قواطع DC TOXLS3-63DC');
    $response->assertSee('وصف المنتج');
    $response->assertSee('https://images.unsplash.com/photo-1518770660439-4636190af475', false);
});

it('renders works as image cards with title and details', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('https://images.unsplash.com/photo-1517048676732-d65bc937f952', false);
    $response->assertSee('تجهيز لوحة توزيع صناعية');
    $response->assertSee('تم تجهيز الموقع بحلول حماية وتحويل وتوزيع تضمن الاستقرار وسهولة الصيانة.');
    $response->assertSee(route('works.show', ['work' => 'industrial-distribution-project', 'lang' => 'ar']), false);
});

it('renders a full work details page with gallery images', function () {
    $response = $this->get(route('works.show', ['work' => 'industrial-distribution-project', 'lang' => 'ar']));

    $response->assertOk();
    $response->assertSee('تجهيز لوحة توزيع صناعية');
    $response->assertSee('Industrial Client');
    $response->assertSee('https://images.unsplash.com/photo-1497436072909-f5e4be5584d2', false);
});

it('stores homepage content in the database', function () {
    expect(Language::query()->count())->toBeGreaterThanOrEqual(2)
        ->and(Page::query()->where('slug', 'home')->exists())->toBeTrue()
        ->and(SiteSetting::query()->exists())->toBeTrue();
});
