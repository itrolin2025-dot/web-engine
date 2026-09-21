<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    public function index($client = null, $pages = null)
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
                'customers_website.customer_type as cust_type'
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
                ->where('customers_websites_layout.page_type', $pages)
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

        $navbarPresets = \App\Http\Controllers\FrontController::getNavbarPresets($categories, $customerType);
        $footerPresets = \App\Http\Controllers\FrontController::getFooterPresets();

        return view('template.index', compact('title', 'website', 'customerName', 'layouts', 'categories', 'products', 'article_categories', 'articles', 'navbarPresets', 'footerPresets'));
    }
}


