<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        $this->configureRateLimiting();

        // 全局共享站点配置给所有视图（表可能尚未创建，需容错）
        try {
            if (Schema::hasTable('site_settings')) {
                View::share('settings', SiteSetting::getAll());
            } else {
                View::share('settings', []);
            }
        } catch (\Throwable $e) {
            View::share('settings', []);
        }
    }

    /**
     * 后台登录 / 前台留言的限流策略，防暴力破解与垃圾留言
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by(strtolower((string) $request->input('login')).'|'.$request->ip());
        });

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
