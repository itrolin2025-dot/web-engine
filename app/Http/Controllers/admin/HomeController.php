<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return auth()->check()
            ? redirect('/admin/dashboard')
            : redirect('/admin/login');
    }
}

