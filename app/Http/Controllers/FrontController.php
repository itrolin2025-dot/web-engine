<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        $websites = DB::table('customers_website')
            ->where('is_active', 1)
            ->get();

        // Halaman awal = showcase slider dengan tab navbar:
        // client | template | section
        $tab = $request->query('tab', 'client');
        if (!in_array($tab, ['client', 'template', 'section'], true)) {
            $tab = 'client';
        }

        $slides = match ($tab) {
            'template' => self::getTemplateSlides(),
            'section'  => self::getSectionSlides(),
            default    => self::getWebsiteSlides(),
        };

        return view('welcome', compact('websites', 'slides', 'tab'));
    }

    /**
     * Menu client website aktif untuk halaman awal.
     * Data dari `customers_website` (is_active = 1); gambar = logo dari
     * `customers_website_identities`. Jika logo tidak ada (atau file hilang)
     * -> broken image.
     *
     * Fungsi terpisah (reusable) agar bisa dipakai di tempat lain.
     */
    public static function getWebsiteSlides()
    {
        $websites = DB::table('customers_website')
            ->leftJoin('customers_website_identities', 'customers_website_identities.customers_website_id', '=', 'customers_website.id')
            ->where('customers_website.is_active', 1)
            ->orderBy('customers_website.id')
            ->select(
                'customers_website.id',
                'customers_website.title',
                'customers_website.domain',
                'customers_website_identities.logo'
            )
            ->get();

        return $websites->map(function ($w) {
            $logo = (!empty($w->logo) && file_exists(public_path($w->logo)))
                ? asset($w->logo)
                : asset('images/default/broken.png');

            return [
                'src'   => $logo,
                'title' => $w->title ?? $w->domain ?? 'Website',
                'url'   => $w->domain ? url('/' . $w->domain) : '#',
            ];
        })->values();
    }

    /**
     * Data slider dari tabel `templates_section` (dipakai untuk tab Section).
     * Menampilkan preview tiap section; fallback ke broken image bila
     * section tidak punya preview atau file-nya tidak ada.
     *
     * Fungsi terpisah (reusable) agar bisa dipakai di tempat lain.
     */
    public static function getSectionSlides()
    {
        $sections = DB::table('templates_section')
            ->orderBy('id')
            ->get(['id', 'name', 'preview']);

        return $sections->map(function ($s) {
            $preview = (!empty($s->preview) && file_exists(public_path($s->preview)))
                ? asset($s->preview)
                : asset('images/default/broken.png');

            return [
                'src'   => $preview,
                'title' => $s->name ?? 'Section',
            ];
        })->values();
    }

    /**
     * Data slider dari tabel `template` (dipakai untuk tab Template).
     * Menampilkan preview tiap template; fallback ke broken image bila
     * template tidak punya preview atau file-nya tidak ada.
     *
     * Dibuat sebagai fungsi terpisah (reusable) karena akan dibutuhkan
     * di tempat lain juga.
     */
    public static function getTemplateSlides()
    {
        $templates = DB::table('template')
            ->orderBy('id')
            ->get(['id', 'name', 'preview']);

        return $templates->map(function ($t) {
            $preview = (!empty($t->preview) && file_exists(public_path($t->preview)))
                ? asset($t->preview)
                : asset('images/default/broken.png');

            return [
                'src'   => $preview,
                'title' => $t->name ?? 'Template',
            ];
        })->values();
    }

    /**
     * Preview template dari table `template` (bukan dari customers_website).
     * Menyusun layout dari `templates_section` milik template tersebut dan
     * memberi default value untuk tiap section dari `templates_sections_content`
     * (key/type/value), sehingga section blade dirender dengan data default.
     */
    public function preview($id)
    {
        $template = DB::table('template')->find($id);
        abort_if(!$template, 404);

        // Layout dari templates_section (bukan customers_websites_layout),
        // tiap section diberi content default dari templates_sections_content
        $layouts = DB::table('templates_section')
            ->where('template_id', $template->id)
            ->where('status', true)
            ->orderBy('position')
            ->get()
            ->map(function ($section) {
                $section->content = json_encode(self::getContentDefaults($section));
                return $section;
            });

        $title = 'Preview — ' . ($template->name ?? 'Template');

        // Variabel global yang dibaca section blade (null-safe preview):
        // domain "preview" agar route('template') tidak error tanpa customers_website.
        $website = (object) [
            'id'          => null,
            'domain'      => 'preview',
            'title'       => $template->name,
            'description' => null,
            'qr_payment'  => null,
        ];

        // Data sample agar section data-driven (product/categories/article)
        // tampil dengan konten default, bukan kosong.
        $categories = collect([
            (object) ['id' => 1, 'name' => 'Category 1', 'code' => 'category-1'],
            (object) ['id' => 2, 'name' => 'Category 2', 'code' => 'category-2'],
            (object) ['id' => 3, 'name' => 'Category 3', 'code' => 'category-3'],
        ]);

        $products = collect([
            (object) ['id' => 1, 'name' => 'Sample Product 1', 'price' => 100000, 'description' => 'Sample product description', 'category_products_id' => 1, 'images' => json_encode(['broken.png'])],
            (object) ['id' => 2, 'name' => 'Sample Product 2', 'price' => 150000, 'description' => 'Sample product description', 'category_products_id' => 2, 'images' => json_encode(['broken.png'])],
            (object) ['id' => 3, 'name' => 'Sample Product 3', 'price' => 200000, 'description' => 'Sample product description', 'category_products_id' => 3, 'images' => json_encode(['broken.png'])],
        ]);

        $articles = collect([
            (object) ['id' => 1, 'title' => 'Sample Article 1', 'slug' => 'sample-article-1', 'description' => 'Sample article content', 'created_at' => now(), 'images' => json_encode(['broken.png']), 'article_category' => 'News', 'author' => 'Sample Author', 'published_date' => now()->format('d M Y')],
            (object) ['id' => 2, 'title' => 'Sample Article 2', 'slug' => 'sample-article-2', 'description' => 'Sample article content', 'created_at' => now()->subDay(), 'images' => json_encode(['broken.png']), 'article_category' => 'News', 'author' => 'Sample Author', 'published_date' => now()->subDay()->format('d M Y')],
            (object) ['id' => 3, 'title' => 'Sample Article 3', 'slug' => 'sample-article-3', 'description' => 'Sample article content', 'created_at' => now()->subDays(2), 'images' => json_encode(['broken.png']), 'article_category' => 'News', 'author' => 'Sample Author', 'published_date' => now()->subDays(2)->format('d M Y')],
        ]);

        $article_categories = collect([
            (object) ['id' => 1, 'name' => 'News'],
        ]);

        $navbarPresets = self::getNavbarPresets($categories, 'Free Edition');
        $footerPresets = self::getFooterPresets();

        // Variabel di-share global agar section blade yang di-render manual
        // (try-catch per section) tetap punya data yang sama dengan @include.
        View::share([
            'title'             => $title,
            'website'           => $website,
            'categories'        => $categories,
            'products'          => $products,
            'articles'          => $articles,
            'article_categories'=> $article_categories,
            'navbarPresets'     => $navbarPresets,
            'footerPresets'     => $footerPresets,
            'navContent'        => $navbarPresets,
        ]);

        return view('template.preview.index', compact('title', 'template', 'layouts'));
    }

    /**
     * Default value per section blade dari `templates_sections_content`.
     * Return: key konten => value (dipakai sebagai $layout->content JSON
     * di tiap partial section). Value disimpan mentah, sesuai yang dibaca
     * section blade ($content['title'], $content['repeater'], dst).
     */
    private static function getContentDefaults($section)
    {
        $contents = DB::table('templates_sections_content')
            ->where('templates_sections_id', $section->id)
            ->whereNull('deleted_at')
            ->get(['key', 'value']);

        // Semua key preset diisi default-nya, supaya section blade yang
        // membaca key secara langsung ($content['image'] tanpa ??) tidak error.
        $config = [];
        foreach (\App\Http\Controllers\admin\TemplateController::getContentPresets() as $preset) {
            $config[$preset['key']] = $preset['default_value'];
        }

        // Repeater default yang kaya key: section blade mengakses berbagai
        // key item secara langsung ($item['image'], $item['title'], dst).
        // Image memakai path broken.png di folder domain "preview" agar
        // slot gambar kosong tetap menampilkan broken image, bukan kosong.
        $previewBroken = 'images/website/preview/broken.png';
        $config['repeater'] = [
            ['label' => 'Tag 1', 'title' => 'Feature Title 1', 'subtitle' => 'Feature subtitle 1', 'description' => 'Feature description 1', 'image' => $previewBroken, 'color' => '#575757', 'title_color' => '#000000', 'subtitle_color' => '#000000', 'button_text' => 'Check', 'button_url' => '#', 'button_color' => '#ffffff', 'button_text_color' => '#000000', 'sort' => '1'],
            ['label' => 'Tag 2', 'title' => 'Feature Title 2', 'subtitle' => 'Feature subtitle 2', 'description' => 'Feature description 2', 'image' => $previewBroken, 'color' => '#575757', 'title_color' => '#000000', 'subtitle_color' => '#000000', 'button_text' => 'Check', 'button_url' => '#', 'button_color' => '#ffffff', 'button_text_color' => '#000000', 'sort' => '2'],
        ];

        // Timpa dengan value yang benar-benar dikonfigurasi untuk section ini
        $contents = DB::table('templates_sections_content')
            ->where('templates_sections_id', $section->id)
            ->whereNull('deleted_at')
            ->get(['key', 'value']);

        foreach ($contents as $c) {
            if (empty($c->key)) {
                continue;
            }
            // Value JSON (repeater, dsb) di-decode ke array agar section blade
            // yang melakukan count()/foreach() langsung menerima array.
            $value = $c->value;
            if (is_string($value) && $value !== '' && ($value[0] === '[' || $value[0] === '{')) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }
            $config[$c->key] = $value;
        }

        // Normalisasi akhir: string ber-JSON (repeater, dsb) di-decode ke array
        foreach ($config as $key => $value) {
            if (is_string($value) && $value !== '' && ($value[0] === '[' || $value[0] === '{')) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $config[$key] = $decoded;
                }
            }
        }

        // Image & background default memakai broken image dari folder domain
        // "preview": blade mengecek !empty($content['image']) lalu menempel
        // path "images/website/{domain}/..." — dengan file broken.png di sana,
        // slot gambar kosong tetap tampil broken image, bukan kosong.
        $config['image'] = 'broken.png';
        $config['background'] = 'broken.png';
        $config['background_image'] = 'broken.png';

        // Normalisasi item repeater: item dari DB bisa jadi hasil copy preset
        // yang tidak punya semua key ($item['image'] dll diakses langsung oleh
        // blade). Merge dengan item default — value dari DB tetap menang.
        if (isset($config['repeater']) && is_array($config['repeater'])) {
            $defaultItem = [
                'label'             => 'Tag',
                'title'             => 'Feature Title',
                'subtitle'          => 'Feature subtitle',
                'description'       => 'Feature description',
                'image'             => 'broken.png',
                'color'             => '#575757',
                'title_color'       => '#000000',
                'subtitle_color'    => '#000000',
                'button_text'       => 'Check',
                'button_url'        => '#',
                'button_color'      => '#ffffff',
                'button_text_color' => '#000000',
                'sort'              => '1',
            ];
            $config['repeater'] = collect($config['repeater'])
                ->map(function ($item) use ($defaultItem) {
                    return is_array($item) ? ($defaultItem + $item) : $item;
                })
                ->sortBy('sort', SORT_NUMERIC)
                ->values()
                ->all();
        }

        // Empty array (repeater []) -> isi dengan item default ber-broken image,
        // supaya tidak ada section yang tampil kosong tanpa gambar.
        if (empty($config['repeater'])) {
            $config['repeater'] = [
                ['label' => 'Tag 1', 'title' => 'Feature Title 1', 'subtitle' => 'Feature subtitle 1', 'description' => 'Feature description 1', 'image' => 'broken.png', 'color' => '#575757', 'title_color' => '#000000', 'subtitle_color' => '#000000', 'button_text' => 'Check', 'button_url' => '#', 'button_color' => '#ffffff', 'button_text_color' => '#000000', 'sort' => '1'],
                ['label' => 'Tag 2', 'title' => 'Feature Title 2', 'subtitle' => 'Feature subtitle 2', 'description' => 'Feature description 2', 'image' => 'broken.png', 'color' => '#575757', 'title_color' => '#000000', 'subtitle_color' => '#000000', 'button_text' => 'Check', 'button_url' => '#', 'button_color' => '#ffffff', 'button_text_color' => '#000000', 'sort' => '2'],
            ];
        }

        return $config;
    }

    public function template($client = null)
    {
        // Join customers_websites_layout → customers_website → customers
        // filtered by domain "elska"
        $website = DB::table('customers_website')
            ->join('customers', 'customers.id', '=', 'customers_website.customer_id')
            ->where('customers_website.domain', $client)
            ->select(
                'customers_website.*',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.customer_type as cust_type'
            )
            ->first();

        $title = $website->title ?? 'Your Brand Page';
        $customerName = $website->customer_name ?? 'Your Brand';
        $customerType = $website->cust_type ?? 'Free Edition';
        
        // Get layout sections for this website, ordered by position
        $layouts = collect();
        if ($website) {
            $layouts = DB::table('customers_websites_layout')
                ->join('templates_section', 'templates_section.id', '=', 'customers_websites_layout.templates_section_id')
                ->join('template', 'template.id', '=', 'templates_section.template_id')
                ->where('customers_websites_layout.customers_website_id', $website->id)
                ->where('customers_websites_layout.status', true)
                ->where('customers_websites_layout.page_type', 'homepage')
                ->orderBy('customers_websites_layout.position')
                ->select(
                    'customers_websites_layout.*',
                    'templates_section.name as section_name',
                    'templates_section.slug as section_slug',
                    'template.path as template_path'
                )
                ->get();

            $categories = DB::table('category_products')
                ->where('customers_website_id', $website->id)
                ->whereNull('deleted_at')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('products')
                        ->whereColumn('products.category_products_id', 'category_products.id')
                        ->whereNull('products.deleted_at');
                })
                ->get();

            $products = DB::table('products')
                ->where('customers_website_id', $website->id)
                ->whereNull('deleted_at')
                ->get();

            $article_categories = DB::table('article_categories')
                ->where('customers_website_id', $website->id)
                ->whereNull('deleted_at')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('articles')
                        ->whereColumn('articles.article_categories_id', 'article_categories.id')
                        ->whereNull('articles.deleted_at');
                })
                ->get();

            $articles = DB::table('articles')
                ->join('article_categories', 'article_categories.id', '=', 'articles.article_categories_id')
                ->select(
                    'articles.*',
                    'article_categories.name as article_category'
                )
                ->where('articles.customers_website_id', $website->id)
                ->whereNull('articles.deleted_at')
                ->get();

        } else {
            $categories = collect();
            $products = collect();
            $article_categories = collect();
            $articles = collect();
        }

        $navbarPresets = self::getNavbarPresets($categories, $customerType);
        $footerPresets = self::getFooterPresets();

        return view('template.index', compact('title', 'website', 'customerName', 'layouts', 'categories', 'products', 'article_categories', 'articles', 'navbarPresets', 'footerPresets'));
    }

    public function selectLayout($client = null)
    {
        $website = DB::table('customers_website')
            ->join('customers', 'customers.id', '=', 'customers_website.customer_id')
            ->where('customers_website.domain', $client)
            ->select(
                'customers_website.*',
                'customers.name as customer_name',
                'customers.email as customer_email'
            )
            ->first();

        $title = $website->title ?? 'Signature Fragrance';

        $sections = DB::table('templates_section')
            ->join('template', 'template.id', '=', 'templates_section.template_id')
            ->where('templates_section.status', true)
            ->orderBy('templates_section.position')
            ->select('templates_section.id as id', 'templates_section.name as name', 'templates_section.slug as slug', 'templates_section.preview as preview', 'template.name as template_name')
            ->get();

        $tabs = $sections->unique('slug')->values();

        return view('template.layout', compact('title', 'website', 'sections', 'tabs'));
    }

    public static function getNavbarPresets($categories = [], $customerType = 'Free Edition')
    {
        $categoryChildren = [];
        foreach ($categories as $cat) {
            $categoryChildren[] = [
                'label' => $cat->name,
                'url' => 'categories/' . $cat->code
            ];
        }

        if ($customerType == 'Free Edition') {
            return [
            'brand' => 'Your Brand',
            'cta_text' => 'Get Started',
            'cta_url' => '#',
            'cta_color' => '#000000',
            'menus' => [
                ['label' => 'Home', 'url' => '', 'samepage' => true],
                ['label' => 'About', 'url' => 'about', 'samepage' => true],
                ['label' => 'Product', 'url' => 'shop', 'samepage' => true],
                ['label' => 'Contact', 'url' => 'contact', 'samepage' => true],
            ]
        ];
        }

        if($customerType == 'Starter Edition'){
            return [
                'brand' => 'Your Brand',
                'cta_text' => 'Get Started',
                'cta_url' => '#',
                'cta_color' => '#000000',
                'menus' => [
                    ['label' => 'Home', 'url' => '', 'samepage' => false],
                    ['label' => 'About', 'url' => 'about', 'samepage' => false],
                    ['label' => 'Article', 'url' => 'article', 'samepage' => false],
                    ['label' => 'Product', 'url' => 'shop', 'samepage' => false],
                    // ['label' => 'Categories', 'url' => 'categories', 'samepage' => false, 'children' => $categoryChildren],
                    ['label' => 'Contact', 'url' => 'contact', 'samepage' => false],
                ]
            ];
            
        }else{
            
            return [
                'brand' => 'Your Brand',
                'cta_text' => 'Get Started',
                'cta_url' => '#',
                'cta_color' => '#000000',
                'menus' => [
                    ['label' => 'Home', 'url' => '', 'samepage' => true],
                    ['label' => 'About', 'url' => 'about', 'samepage' => true],
                    ['label' => 'Product', 'url' => 'shop', 'samepage' => true],
                    ['label' => 'Categories', 'url' => 'categories', 'samepage' => true, 'children' => $categoryChildren],
                    ['label' => 'Contact', 'url' => 'contact', 'samepage' => true],
                ]
            ];
        }
    }

    public static function getFooterPresets()
    {
        return [
            'title' => 'Menus',
            'footer_menu' => [
                ['label' => 'Home', 'url' => '', 'type' => 'child'],
                ['label' => 'About', 'url' => 'about', 'type' => 'child'],
                ['label' => 'Shop', 'url' => 'shop', 'type' => 'child'],
                ['label' => 'Contact', 'url' => 'contact', 'type' => 'child'],
            ],
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];
    }
}
