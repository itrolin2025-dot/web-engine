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

        // Ambil section layout berdasarkan page_type ($pages = 'shop', 'about', dll)
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

        return view('template.index', compact('title', 'website', 'layouts', 'pages'));
    }
}
