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
            // Content Security Policy：限制资源加载来源，防 XSS 与数据注入
            'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: blob:; font-src 'self'; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self';",
            // HTTPS 强制：浏览器在 1 年内仅通过 HTTPS 访问本站
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        ];

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}
