<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SiteSettingController;
use Illuminate\Support\Facades\Route;

// 后台路径前缀（可在 .env 的 ADMIN_PREFIX 中配置，避免暴露为 admin）
// 注意：URL 前缀可配置，但路由名称仍统一为 admin.*，便于代码内部引用。
$prefix = config('ADMIN_PREFIX', 'manage');

// ========== 后台认证 ==========
Route::prefix($prefix)->name('admin.')->group(function () {
    // 未登录可访问
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ========== 后台受保护路由 ==========
Route::prefix($prefix)->name('admin.')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 个人资料 / 修改密码
    Route::get('/profile/password', [AuthController::class, 'showChangePassword'])->name('profile.password');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');

    // 站点设置
    Route::get('/settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');

    // 首页板块管理
    Route::resource('sections', HomepageSectionController::class)->except(['show']);
    // 局部：仅返回表格行（供弹窗提交后无刷新刷新列表）
    Route::get('/sections/rows', [HomepageSectionController::class, 'rows'])->name('sections.rows');

    // 单页管理
    Route::resource('pages', PageController::class)->except(['show']);
    Route::get('/pages/rows', [PageController::class, 'rows'])->name('pages.rows');

    // 产品服务管理
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/products/rows', [ProductController::class, 'rows'])->name('products.rows');
    Route::resource('categories', CategoryController::class)->except(['show', 'create']);
    Route::get('/categories/rows', [CategoryController::class, 'rows'])->name('categories.rows');

    // 新闻文章管理
    Route::resource('posts', PostController::class)->except(['show']);
    Route::get('/posts/rows', [PostController::class, 'rows'])->name('posts.rows');
    Route::resource('post-categories', PostCategoryController::class)->except(['show', 'create'])
        ->names('post-categories');
    Route::get('/post-categories/rows', [PostCategoryController::class, 'rows'])->name('post-categories.rows');

    // 留言管理
    Route::resource('messages', ContactMessageController::class)->except(['create', 'store', 'edit', 'update']);
});
