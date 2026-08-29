<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {
        $websites = DB::table('customers_website')
            ->where('is_active', 1)
            ->get();

        return view('welcome', compact('websites'));
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
                'customers.email as customer_email'
            )
            ->first();

        $title = $website->title ?? 'Signature Fragrance';

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
                ->where('customers_id', $website->customer_id)
                ->whereNull('deleted_at')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('products')
                        ->whereColumn('products.category_products_id', 'category_products.id')
                        ->whereNull('products.deleted_at');
                })
                ->get();

            $products = DB::table('products')
                ->where('customers_id', $website->customer_id)
                ->whereNull('deleted_at')
                ->get();

            $article_categories = DB::table('article_categories')
                ->where('customers_id', $website->customer_id)
                ->whereNull('deleted_at')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('articles')
                        ->whereColumn('articles.article_categories_id', 'article_categories.id')
                        ->whereNull('articles.deleted_at');
                })
                ->get();

            $articles = DB::table('articles')
                ->where('customers_id', $website->customer_id)
                ->whereNull('deleted_at')
                ->get();

        } else {
            $categories = collect();
            $products = collect();
            $article_categories = collect();
            $articles = collect();
        }

        return view('template.index', compact('title', 'website', 'layouts', 'categories', 'products', 'article_categories', 'articles'));
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
}
