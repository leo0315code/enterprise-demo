<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 全局共享站点配置给所有视图（表可能尚未创建）
        if (Schema::hasTable('site_settings')) {
            View::share('settings', SiteSetting::getAll());
        } else {
            View::share('settings', collect());
        }
    }
}
