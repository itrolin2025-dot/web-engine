<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PagesController;

Route::get('/', [FrontController::class, 'index'])->name('home');

// Admin routes HARUS di atas wildcard agar tidak tertangkap /{client}/{pages}
Route::prefix('admin')->name('admin.')->group(function () {
    require base_path('routes/admin.php');
    require base_path('routes/auth.php');
});

// Wildcard routes (harus di bawah admin prefix)
Route::get('/select-layout', [FrontController::class, 'selectLayout'])->name('select-layout');
Route::get('/{client}/checkout', [PagesController::class, 'checkout'])->name('pages.checkout');
Route::post('/{client}/checkout/order', [PagesController::class, 'placeOrder'])->name('pages.checkout.order');
Route::get('/{client}/checkout/success', [PagesController::class, 'orderSuccess'])->name('pages.checkout.success');
Route::get('/{client}/{pages}', [PagesController::class, 'index'])->name('pages');
Route::get('/{client}', [FrontController::class, 'template'])->name('template');