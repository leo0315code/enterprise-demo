<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

// 关于我们（单页）
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

// 产品服务
Route::get('/products', function () {
    return view('products.index');
})->name('products.index');
Route::get('/products/{slug}', function ($slug) {
    return view('products.show', compact('slug'));
})->name('products.show');

// 新闻文章
Route::get('/news', function () {
    return view('posts.index');
})->name('posts.index');
Route::get('/news/{slug}', function ($slug) {
    return view('posts.show', compact('slug'));
})->name('posts.show');

// 联系我们
Route::get('/contact', function () {
    return view('contact.index');
})->name('contact');
Route::post('/contact', function () {
    return redirect()->back()->with('success', '留言已提交，我们会尽快与您联系');
})->name('contact.submit');
