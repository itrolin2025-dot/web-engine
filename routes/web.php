<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/{any}', [FrontController::class, 'template'])->name('template');

require __DIR__ . '/auth.php';
