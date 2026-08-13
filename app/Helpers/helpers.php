<?php

use App\Models\SiteSetting;

if (!function_exists('setting')) {
    /**
     * 获取站点配置值
     *
     * @param string $key 配置键
     * @param mixed $default 默认值
     * @return mixed
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }
}
