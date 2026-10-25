<?php

use App\Http\Controllers\AlerteController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExempleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sites\PageController;
use App\Http\Controllers\Sites\SiteController;
use App\Http\Controllers\TesterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/exemple', [ExempleController::class, 'index'])->name('exemple');

Route::get('/badge/{page}.svg', [BadgeController::class, 'svg'])->name('badge');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/alertes', [AlerteController::class, 'index'])->name('alertes.index');
    Route::get('/pages/{page}', [PageController::class, 'show'])->name('pages.show');
    Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
    Route::get('/sites/create', [SiteController::class, 'create'])->name('sites.create');
    Route::post('/sites', [SiteController::class, 'store'])->name('sites.store');
    Route::get('/sites/{site}', [SiteController::class, 'show'])->name('sites.show');
    Route::delete('/sites/{site}', [SiteController::class, 'destroy'])->name('sites.destroy');
    Route::post('/sites/{site}/sitemap', [SiteController::class, 'importerSitemap'])->name('sites.sitemap');
    Route::post('/sites/{site}/pages', [PageController::class, 'store'])->name('sites.pages.store');
    Route::patch('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
});

Route::get('/tester', [TesterController::class, 'formulaire'])->name('tester');
Route::post('/tester', [TesterController::class, 'analyser'])->name('tester.analyser');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
