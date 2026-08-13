<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// 首页
Route::get('/', [HomeController::class, 'index'])->name('home');

// 单页 (关于/联系等，由后台 Page 管理)
Route::get('/p/{slug}', [PageController::class, 'show'])->name('page.show');

// 关于我们 / 联系我们 快捷路由（映射到 Page slug）
Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about')->name('about');
Route::get('/contact', [PageController::class, 'show'])->defaults('slug', 'contact')->name('contact');

// 产品服务
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// 新闻文章
Route::get('/news', [PostController::class, 'index'])->name('posts.index');
Route::get('/news/{slug}', [PostController::class, 'show'])->name('posts.show');

// 联系我们 - 留言提交
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');
