<?php

use App\Http\Controllers\Web\AnnouncementWebController;
use App\Http\Controllers\Web\DepartmentWebController;
use App\Http\Controllers\Web\GalleryWebController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\NewsWebController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Route khusus diprioritaskan sebelum alias CMS (PRD 8.3).
Route::get('/jurusan', [DepartmentWebController::class, 'index'])->name('departments.index');
Route::get('/jurusan/{slug}', [DepartmentWebController::class, 'show'])->name('departments.show');
Route::get('/berita', [NewsWebController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsWebController::class, 'show'])->name('news.show');
Route::get('/pengumuman', [AnnouncementWebController::class, 'index'])->name('announcements.index');
Route::get('/pengumuman/{slug}', [AnnouncementWebController::class, 'show'])->name('announcements.show');
Route::get('/galeri', [GalleryWebController::class, 'index'])->name('galleries.index');
Route::get('/galeri/{slug}', [GalleryWebController::class, 'show'])->name('galleries.show');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

// Alias opsional untuk halaman CMS. Slug global harus unik antar jenis konten.
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '^(?!admin|api|storage|build|jurusan|berita|pengumuman|galeri|page|sitemap\.xml)[a-z0-9-]+$')
    ->name('cms.alias');
