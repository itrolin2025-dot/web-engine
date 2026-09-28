<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
