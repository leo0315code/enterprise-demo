<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SiteSettingController;
use Illuminate\Support\Facades\Route;

// ========== 后台认证 ==========
Route::prefix('admin')->name('admin.')->group(function () {
    // 未登录可访问
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ========== 后台受保护路由 ==========
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 站点设置
    Route::get('/settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');

    // 首页板块管理
    Route::resource('sections', HomepageSectionController::class)->except(['show']);

    // 单页管理
    Route::resource('pages', PageController::class)->except(['show']);

    // 产品服务管理
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);

    // 新闻文章管理
    Route::resource('posts', PostController::class)->except(['show']);
    Route::resource('post-categories', PostCategoryController::class)->except(['show', 'create', 'edit'])
        ->names('post-categories');
});
