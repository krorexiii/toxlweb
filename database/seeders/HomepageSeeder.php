<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Section;
use App\Models\SectionTranslation;
use App\Models\SiteSetting;
use App\Models\Work;
use App\Models\WorkTranslation;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $arabic = Language::query()->updateOrCreate(
            ['code' => 'ar'],
            [
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $english = Language::query()->updateOrCreate(
            ['code' => 'en'],
            [
                'name' => 'English',
                'native_name' => 'English',
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        SiteSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'TOXL',
                'site_tagline' => 'Save Money & Energy',
                'logo_path' => null,
                'favicon_path' => null,
                'primary_phone' => '07700467159',
                'secondary_phone' => '9647700467159',
                'primary_email' => 'info@toxl.test',
                'address' => 'شارع الصناعة - بغداد',
                'map_embed_url' => 'https://www.google.com/maps?q=%D8%B4%D8%A7%D8%B1%D8%B9%20%D8%A7%D9%84%D8%B5%D9%86%D8%A7%D8%B9%D8%A9%20%D8%A8%D8%BA%D8%AF%D8%A7%D8%AF&output=embed',
                'social_links' => [
                    ['label' => 'WhatsApp', 'url' => 'https://wa.me/9647700467159'],
                    ['label' => 'Facebook', 'url' => 'https://facebook.com/toxl'],
                    ['label' => 'Instagram', 'url' => 'https://instagram.com/toxl'],
                ],
                'localized_content' => [
                    'ar' => [
                        'address' => 'شارع الصناعة - بغداد',
                        'tagline' => 'وفر المال والطاقة',
                        'footer' => 'جميع الحقوق محفوظة.',
                        'contact_note' => 'متاحون للرد على واتساب خلال أوقات الدوام',
                    ],
                    'en' => [
                        'address' => 'Al-Sinaa Street - Baghdad',
                        'tagline' => 'Save Money & Energy',
                        'footer' => 'All rights reserved.',
                        'contact_note' => 'We reply on WhatsApp during business hours',
                    ],
                ],
            ]
        );

        $page = Page::query()->updateOrCreate(
            ['slug' => 'home'],
            [
                'template' => 'home',
                'is_active' => true,
                'meta' => [
                    'title' => 'TOXL',
                    'description' => 'Electrical, protection, solar and distribution solutions.',
                ],
            ]
        );

        $sections = [
            'about' => [
                'theme' => 'dark',
                'sort_order' => 1,
                'data' => [
                    'stats' => [
                        ['value' => '10+', 'label_key' => 'products'],
                        ['value' => '24/7', 'label_key' => 'support'],
                        ['value' => '100%', 'label_key' => 'quality'],
                    ],
                ],
                'translations' => [
                    'ar' => [
                        'title' => 'حلول كهربائية وطاقة شمسية موثوقة',
                        'subtitle' => 'القسم التعريفي',
                        'body' => 'نوفر منتجات حماية وتحويل وتوزيع كهربائي عالية الأداء لأنظمة الطاقة الشمسية والتطبيقات الصناعية والسكنية، مع هوية بصرية ومحتوى قابل للإدارة بالكامل من قاعدة البيانات.',
                        'cta_primary_label' => 'استعرض المنتجات',
                        'cta_primary_url' => '#products',
                        'cta_secondary_label' => 'تواصل معنا',
                        'cta_secondary_url' => '#contact',
                        'items' => [
                            'منتجات عالية الاعتمادية.',
                            'محتوى متعدد اللغات من قاعدة البيانات.',
                            'تصميم متجاوب لكل الشاشات.',
                        ],
                    ],
                    'en' => [
                        'title' => 'Reliable electrical and solar solutions',
                        'subtitle' => 'About us',
                        'body' => 'We provide high-performance protection, transfer, and distribution products for solar, industrial, and residential use with a visual identity and content fully managed from the database.',
                        'cta_primary_label' => 'View products',
                        'cta_primary_url' => '#products',
                        'cta_secondary_label' => 'Contact us',
                        'cta_secondary_url' => '#contact',
                        'items' => [
                            'High-reliability products.',
                            'Multilingual content from the database.',
                            'Responsive design for every screen.',
                        ],
                    ],
                ],
            ],
            'products' => [
                'theme' => 'dark',
                'sort_order' => 2,
                'data' => [],
                'translations' => [
                    'ar' => [
                        'title' => 'المنتجات',
                        'subtitle' => 'قسم المنتجات',
                        'body' => 'مجموعة من أبرز منتجات TOXL المعروضة بشكل ديناميكي من قاعدة البيانات.',
                        'items' => [],
                    ],
                    'en' => [
                        'title' => 'Products',
                        'subtitle' => 'Our product range',
                        'body' => 'A featured selection of TOXL products rendered dynamically from the database.',
                        'items' => [],
                    ],
                ],
            ],
            'works' => [
                'theme' => 'muted',
                'sort_order' => 3,
                'data' => [],
                'translations' => [
                    'ar' => [
                        'title' => 'الأعمال',
                        'subtitle' => 'قسم الأعمال',
                        'body' => 'نماذج من الأعمال والمشاريع التي تم تنفيذها أو تجهيزها بمنتجات TOXL.',
                        'items' => [],
                    ],
                    'en' => [
                        'title' => 'Works',
                        'subtitle' => 'Recent projects',
                        'body' => 'Examples of projects delivered or equipped with TOXL products.',
                        'items' => [],
                    ],
                ],
            ],
            'contact' => [
                'theme' => 'dark',
                'sort_order' => 4,
                'data' => [
                    'whatsapp_url' => 'https://wa.me/9647700467159',
                ],
                'translations' => [
                    'ar' => [
                        'title' => 'تواصل معنا',
                        'subtitle' => 'قسم التواصل',
                        'body' => 'للاستفسار وطلب الأسعار، تواصل معنا مباشرة عبر الواتساب أو قم بزيارتنا في شارع الصناعة - بغداد.',
                        'cta_primary_label' => 'راسلنا على واتساب',
                        'cta_primary_url' => 'https://wa.me/9647700467159',
                        'items' => ['واتساب', 'الهاتف', 'الموقع على الخريطة'],
                    ],
                    'en' => [
                        'title' => 'Contact us',
                        'subtitle' => 'Get in touch',
                        'body' => 'For inquiries and pricing, contact us directly via WhatsApp or visit us on Al-Sinaa Street, Baghdad.',
                        'cta_primary_label' => 'Chat on WhatsApp',
                        'cta_primary_url' => 'https://wa.me/9647700467159',
                        'items' => ['WhatsApp', 'Phone', 'Map location'],
                    ],
                ],
            ],
        ];

        foreach ($sections as $key => $sectionData) {
            $section = Section::query()->updateOrCreate(
                ['page_id' => $page->id, 'key' => $key],
                [
                    'theme' => $sectionData['theme'],
                    'sort_order' => $sectionData['sort_order'],
                    'is_active' => true,
                    'data' => $sectionData['data'],
                ]
            );

            foreach ($sectionData['translations'] as $languageCode => $translation) {
                $languageId = $languageCode === 'ar' ? $arabic->id : $english->id;

                SectionTranslation::query()->updateOrCreate(
                    ['section_id' => $section->id, 'language_id' => $languageId],
                    $translation
                );
            }
        }

        $products = [
            [
                'slug' => 'toxls3-63dc',
                'sku' => 'TOXLS3-63DC',
                'image_path' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1563770660941-10a63607654d?auto=format&fit=crop&w=1200&q=80',
                ],
                'sort_order' => 1,
                'is_featured' => true,
                'specifications' => [
                    ['label' => 'Voltage', 'value' => 'Up to 1000V DC'],
                    ['label' => 'Current', 'value' => '6A–63A'],
                    ['label' => 'Protection', 'value' => 'IP20'],
                ],
                'translations' => [
                    'ar' => [
                        'name' => 'قواطع DC TOXLS3-63DC',
                        'short_description' => 'قواطع تيار مستمر مخصصة لأنظمة الطاقة الشمسية.',
                        'description' => 'قواطع تيار مستمر توفر أداءً عاليًا واعتمادية ممتازة لأنظمة الطاقة الشمسية.',
                        'features' => ['1P إلى 4P', 'حتى 1000V DC', 'تيارات من 6A إلى 63A'],
                    ],
                    'en' => [
                        'name' => 'DC Circuit Breakers TOXLS3-63DC',
                        'short_description' => 'DC breakers designed for solar power systems.',
                        'description' => 'Reliable DC circuit breakers built for solar energy applications and high-performance protection.',
                        'features' => ['1P to 4P', 'Up to 1000V DC', 'Current range from 6A to 63A'],
                    ],
                ],
            ],
            [
                'slug' => 'mccb-dc',
                'sku' => 'MCCB-DC',
                'image_path' => 'https://images.unsplash.com/photo-1584277261846-c6a1672ed979?auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1555664424-778a1e5e1b48?auto=format&fit=crop&w=1200&q=80',
                ],
                'sort_order' => 2,
                'is_featured' => true,
                'specifications' => [
                    ['label' => 'Current', 'value' => '63A–400A'],
                    ['label' => 'Voltage', 'value' => '1000V DC'],
                    ['label' => 'Trip', 'value' => 'Thermal-magnetic'],
                ],
                'translations' => [
                    'ar' => [
                        'name' => 'MCCB (DC)',
                        'short_description' => 'قواطع حالة مصبوبة بآلية فصل حرارية مغناطيسية.',
                        'description' => 'حل متقدم للتوزيع والحماية مع ملحقات متعددة وقدرة تحمل عالية.',
                        'features' => ['63A إلى 400A', '1000V DC', 'ملحقات متعددة'],
                    ],
                    'en' => [
                        'name' => 'MCCB (DC)',
                        'short_description' => 'Molded case breakers with thermal-magnetic protection.',
                        'description' => 'An advanced protection and distribution solution with multiple accessories and high durability.',
                        'features' => ['63A to 400A', '1000V DC', 'Multiple accessories'],
                    ],
                ],
            ],
            [
                'slug' => 'automatic-transfer-switch',
                'sku' => 'ATS',
                'image_path' => 'https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&w=1200&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1580894908361-967195033215?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1200&q=80',
                ],
                'sort_order' => 3,
                'is_featured' => true,
                'specifications' => [
                    ['label' => 'Rated current', 'value' => '16A–100A'],
                    ['label' => 'Transfer speed', 'value' => '< 50ms'],
                    ['label' => 'Voltage', 'value' => 'AC690V'],
                ],
                'translations' => [
                    'ar' => [
                        'name' => 'Automatic Transfer Switch (ATS)',
                        'short_description' => 'تحويل مصدر الطاقة بسرعة وكفاءة لتأمين الاستمرارية.',
                        'description' => 'مفتاح تحويل أوتوماتيكي يضمن استمرارية التشغيل وسرعة استجابة ممتازة.',
                        'features' => ['سرعة أقل من 50ms', '16A إلى 100A', 'عمر تشغيلي مرتفع'],
                    ],
                    'en' => [
                        'name' => 'Automatic Transfer Switch (ATS)',
                        'short_description' => 'Fast and efficient source transfer for continuity.',
                        'description' => 'An automatic transfer solution that keeps operations running with fast switching response.',
                        'features' => ['Transfer speed below 50ms', '16A to 100A', 'Long operating life'],
                    ],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $translations = $productData['translations'];
            unset($productData['translations']);

            $product = Product::query()->updateOrCreate(
                ['slug' => $productData['slug']],
                $productData + ['is_active' => true]
            );

            foreach ($translations as $languageCode => $translation) {
                ProductTranslation::query()->updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'language_id' => $languageCode === 'ar' ? $arabic->id : $english->id,
                    ],
                    $translation
                );
            }
        }

        $works = [
            [
                'slug' => 'industrial-distribution-project',
                'image_path' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1400&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1497436072909-f5e4be5584d2?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1400&q=80',
                ],
                'client_name' => 'Industrial Client',
                'location' => 'Baghdad',
                'completed_at' => now()->subMonths(4)->toDateString(),
                'sort_order' => 1,
                'is_featured' => true,
                'metrics' => [
                    ['label' => 'Panels', 'value' => '24'],
                    ['label' => 'Protection points', 'value' => '60+'],
                ],
                'translations' => [
                    'ar' => [
                        'title' => 'تجهيز لوحة توزيع صناعية',
                        'short_description' => 'حل توزيع وحماية لموقع صناعي في بغداد.',
                        'description' => 'تم تجهيز الموقع بحلول حماية وتحويل وتوزيع تضمن الاستقرار وسهولة الصيانة.',
                        'highlights' => ['تنفيذ منظم', 'اعتمادية عالية', 'سهولة التوسعة'],
                    ],
                    'en' => [
                        'title' => 'Industrial distribution panel delivery',
                        'short_description' => 'Protection and distribution solution for an industrial site in Baghdad.',
                        'description' => 'The site was equipped with protection, transfer, and distribution solutions designed for stability and easier maintenance.',
                        'highlights' => ['Well-structured delivery', 'High reliability', 'Easy expansion'],
                    ],
                ],
            ],
            [
                'slug' => 'solar-protection-installation',
                'image_path' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=1400&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1466611653911-95081537e5b7?auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=1400&q=80',
                ],
                'client_name' => 'Solar Integrator',
                'location' => 'Baghdad',
                'completed_at' => now()->subMonths(2)->toDateString(),
                'sort_order' => 2,
                'is_featured' => true,
                'metrics' => [
                    ['label' => 'String lines', 'value' => '12'],
                    ['label' => 'ATS units', 'value' => '4'],
                ],
                'translations' => [
                    'ar' => [
                        'title' => 'تركيب حماية لمنظومة شمسية',
                        'short_description' => 'تجهيز منظومة شمسية بعناصر حماية وتحويل موثوقة.',
                        'description' => 'مشروع يركز على الجودة، السلامة، والاستجابة السريعة عند التحويل بين المصادر.',
                        'highlights' => ['دعم الطاقة الشمسية', 'تشغيل آمن', 'استمرارية ممتازة'],
                    ],
                    'en' => [
                        'title' => 'Solar protection installation',
                        'short_description' => 'A solar system equipped with reliable protection and transfer components.',
                        'description' => 'A project focused on quality, safety, and quick source switching response.',
                        'highlights' => ['Solar-ready', 'Safe operation', 'Strong continuity'],
                    ],
                ],
            ],
        ];

        foreach ($works as $workData) {
            $translations = $workData['translations'];
            unset($workData['translations']);

            $work = Work::query()->updateOrCreate(
                ['slug' => $workData['slug']],
                $workData + ['is_active' => true]
            );

            foreach ($translations as $languageCode => $translation) {
                WorkTranslation::query()->updateOrCreate(
                    [
                        'work_id' => $work->id,
                        'language_id' => $languageCode === 'ar' ? $arabic->id : $english->id,
                    ],
                    $translation
                );
            }
        }
    }
}
