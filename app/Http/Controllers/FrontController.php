<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function elska()
    {
        $website = DB::table('customers_website')
            ->where('domain', 'elska')
            ->first();

        $title = $website->title ?? 'Signature Fragrance';

        return view('client.elska.landing.index', compact('title'));
    }

    public function elskaShop()
    {
        $file = resource_path('views/client/elska/ecommerce/index.html');
        return response()->file($file);
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
                ->orderBy('customers_websites_layout.position')
                ->select(
                    'customers_websites_layout.*',
                    'templates_section.name as section_name',
                    'templates_section.slug as section_slug',
                    'template.path as template_path'
                )
                ->get();
        }

        return view('template.index', compact('title', 'website', 'layouts'));
    }
}
