<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PagesController;

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/{client}/{pages}', [PagesController::class, 'index'])->name('pages');
Route::get('/{any}', [FrontController::class, 'template'])->name('template');

require __DIR__ . '/auth.php';
