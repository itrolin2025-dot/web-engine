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

    public function template()
    {

        $website = DB::table('customers_website')
            ->where('domain', 'elska')
            ->first();

        $title = $website->title ?? 'Signature Fragrance';

        return view('template.index', compact('title'));
    }
}
