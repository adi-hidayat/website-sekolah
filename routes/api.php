<?php

use App\Http\Controllers\Api\V1\AnnouncementController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\DirectoryController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\SiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/site-settings', [SiteController::class, 'settings'])->name('settings');
    Route::get('/menus/{location}', [SiteController::class, 'menu'])->name('menus.show');
    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/{slug}', [DepartmentController::class, 'show'])->name('departments.show');
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news-categories', [NewsController::class, 'categories'])->name('news.categories');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{slug}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/facilities', [DirectoryController::class, 'facilities'])->name('facilities');
    Route::get('/achievements', [DirectoryController::class, 'achievements'])->name('achievements');
    Route::get('/galleries', [DirectoryController::class, 'galleries'])->name('galleries');
});
