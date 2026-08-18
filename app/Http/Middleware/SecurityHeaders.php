<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 为所有响应补充基础安全响应头。
 * 已存在的头不会被覆盖（如控制器内显式设置）。
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            // 禁止浏览器猜测 MIME 类型，防 MIME 嗅探攻击
            'X-Content-Type-Options' => 'nosniff',
            // 仅允许同源页面嵌套本站，防点击劫持
            'X-Frame-Options' => 'SAMEORIGIN',
            // 跨站请求仅发送来源（不含路径），兼顾统计与隐私
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            // 本站不需要摄像头/麦克风/定位等敏感硬件能力
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
        ];

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}
