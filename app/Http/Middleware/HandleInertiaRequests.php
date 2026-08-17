<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'siteName' => function () {
                return function_exists('setting')
                    ? setting('site_name', config('app.name'))
                    : config('app.name');
            },
            'auth' => [
                'user' => $request->user()
                    ? [
                        'name' => $request->user()->name,
                        'email' => $request->user()->email,
                    ]
                    : null,
            ],
        ];
    }

    /**
     * 让前端可通过 route() 助手解析 URL（配合 ziggy）。
     */
    public function rootView(Request $request): string
    {
        // 后台页面统一使用 Inertia 根模板；其余仍走各自 Blade 视图
        return parent::rootView($request);
    }
}
