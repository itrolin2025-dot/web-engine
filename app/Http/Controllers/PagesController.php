<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    public function index($client = null, $pages = null)
    {
        // Ambil data website berdasarkan domain/client
        $website = DB::table('customers_website')
            ->join('customers', 'customers.id', '=', 'customers_website.customer_id')
            ->where('customers_website.domain', $client)
            ->select(
                'customers_website.*',
                'customers.name as customer_name',
                'customers.email as customer_email'
            )
            ->first();

        if (!$website) {
            abort(404);
        }

        $title = $website->title ?? 'Signature Fragrance';

        $layouts = collect();
        if ($website) {
            // Ambil section layout berdasarkan page_type ($pages = 'shop', 'about', dll)
            $layoutQuery = function (string $pageType) use ($website) {
                return DB::table('customers_websites_layout')
                    ->join('templates_section', 'templates_section.id', '=', 'customers_websites_layout.templates_section_id')
                    ->join('template', 'template.id', '=', 'templates_section.template_id')
                    ->where('customers_websites_layout.customers_website_id', $website->id)
                    ->where('customers_websites_layout.status', true)
                    ->where('customers_websites_layout.page_type', $pageType)
                    ->orderBy('customers_websites_layout.position')
                    ->select(
                        'customers_websites_layout.*',
                        'templates_section.name as section_name',
                        'templates_section.slug as section_slug',
                        'template.path as template_path'
                    );
            };

            $layouts = $layoutQuery($pages)->get();

            // Fallback: jika page ini belum punya layout (misal 'product', 'shop'),
            // pakai layout homepage agar section (termasuk tombol add-to-cart) tetap tampil.
            if ($layouts->isEmpty()) {
                $layouts = $layoutQuery('homepage')->get();
            }

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
                ->select('articles.*', 'article_categories.name as article_category')
                ->where('articles.customers_website_id', $website->id)
                ->whereNull('articles.deleted_at')
                ->get();

        } else {
            $categories = collect();
            $products = collect();
            $article_categories = collect();
            $articles = collect();
        }

        // Preset menu navbar & footer (juga dipakai section navbar/footer yang
        // membaca $navContent['menus'], mis. template.jolie.navbar)
        $categoryChildren = [];
        foreach ($categories as $cat) {
            $categoryChildren[] = [
                'label' => $cat->name,
                'url' => 'categories/' . $cat->code
            ];
        }

        $navbarPresets = [
            'brand' => 'Your Brand',
            'cta_text' => 'Get Started',
            'cta_url' => '#',
            'cta_color' => '#000000',
            'menus' => [
                ['label' => 'Home', 'url' => ''],
                ['label' => 'About', 'url' => 'about'],
                ['label' => 'Shop', 'url' => 'shop'],
                ['label' => 'Categories', 'url' => 'categories', 'children' => $categoryChildren],
                ['label' => 'Contact', 'url' => 'contact'],
            ]
        ];

        $footerPresets = [
            'title' => 'Menus',
            'footer_menu' => [
                ['label' => 'Home', 'url' => '', 'type' => 'child'],
                ['label' => 'About', 'url' => 'about', 'type' => 'child'],
                ['label' => 'Shop', 'url' => 'shop', 'type' => 'child'],
                ['label' => 'Contact', 'url' => 'contact', 'type' => 'child'],
            ],
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        ];

        return view('template.index', compact('title', 'website', 'layouts', 'pages', 'categories', 'products', 'article_categories', 'articles', 'navbarPresets', 'footerPresets'));
    }
}
