<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminGenreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/kebijakan/{page}', fn ($page) => view('legal', ['page' => $page]))
    ->whereIn('page', ['privasi', 'ketentuan', 'refund'])->name('legal');

Route::middleware(['auth', 'subscriber'])->group(function () {
    Route::get('/katalog', [PublicController::class, 'catalog'])->name('catalog');
    Route::get('/film/{film:slug}', [PublicController::class, 'show'])->name('film');
    Route::get('/tonton/{film:slug}', [PublicController::class, 'watch'])->name('watch');
});

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/daftar', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');
    Route::get('/akun', [PublicController::class, 'account'])->name('account');
    Route::put('/akun', [AuthController::class, 'updateProfile'])->name('account.update');
    Route::get('/pembayaran', [CheckoutController::class, 'page'])->name('checkout.page');
    Route::post('/berlangganan', [CheckoutController::class, 'start'])->name('checkout.start');
    Route::get('/pembayaran/selesai', [CheckoutController::class, 'finish'])->name('checkout.finish');
});

Route::post('/midtrans/webhook', [CheckoutController::class, 'webhook']);

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/transaksi', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/pengguna', [AdminController::class, 'users'])->name('users');
    Route::resource('films', AdminController::class)->except(['show'])
        ->parameters(['films' => 'film'])
        ->names(['index' => 'films.index', 'create' => 'films.create', 'store' => 'films.store', 'edit' => 'films.edit', 'update' => 'films.update']);
    Route::resource('genres', AdminGenreController::class)->except(['show', 'create', 'edit'])
        ->parameters(['genres' => 'genre'])
        ->names(['index' => 'genres.index', 'store' => 'genres.store', 'update' => 'genres.update', 'destroy' => 'genres.destroy']);
});
