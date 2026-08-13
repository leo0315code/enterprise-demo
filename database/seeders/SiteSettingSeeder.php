<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ========== 基础信息 ==========
            [
                'group' => 'general', 'key' => 'site_name', 'value' => '云创科技',
                'label' => '站点名称', 'type' => 'text', 'sort' => 1,
                'description' => '显示在网站标题和导航栏',
            ],
            [
                'group' => 'general', 'key' => 'site_logo', 'value' => '',
                'label' => '站点Logo', 'type' => 'image', 'sort' => 2,
                'description' => '上传Logo图片，留空则显示文字',
            ],
            [
                'group' => 'general', 'key' => 'site_slogan', 'value' => '专注互联网技术创新',
                'label' => '站点口号', 'type' => 'text', 'sort' => 3,
                'description' => '首页Slogan展示',
            ],
            [
                'group' => 'general', 'key' => 'site_description', 'value' => '云创科技是一家专注于互联网技术研发、软件开发与数字化转型的科技企业，致力于为客户提供高效、创新的解决方案。',
                'label' => '站点简介', 'type' => 'textarea', 'sort' => 4,
                'description' => '用于SEO和首页展示',
            ],
            [
                'group' => 'general', 'key' => 'icp_number', 'value' => '',
                'label' => 'ICP备案号', 'type' => 'text', 'sort' => 5,
                'description' => '底部备案信息',
            ],

            // ========== 联系方式 ==========
            [
                'group' => 'contact', 'key' => 'contact_phone', 'value' => '400-888-8888',
                'label' => '联系电话', 'type' => 'text', 'sort' => 1,
                'description' => '客服热线',
            ],
            [
                'group' => 'contact', 'key' => 'contact_email', 'value' => 'contact@example.com',
                'label' => '联系邮箱', 'type' => 'text', 'sort' => 2,
                'description' => '',
            ],
            [
                'group' => 'contact', 'key' => 'contact_address', 'value' => '北京市朝阳区科技园路1号',
                'label' => '公司地址', 'type' => 'text', 'sort' => 3,
                'description' => '',
            ],
            [
                'group' => 'contact', 'key' => 'contact_wechat', 'value' => '',
                'label' => '微信公众号', 'type' => 'image', 'sort' => 4,
                'description' => '上传二维码图片',
            ],
            [
                'group' => 'contact', 'key' => 'work_time', 'value' => '周一至周五 9:00-18:00',
                'label' => '工作时间', 'type' => 'text', 'sort' => 5,
                'description' => '',
            ],

            // ========== 社交媒体 ==========
            [
                'group' => 'social', 'key' => 'social_weibo', 'value' => '',
                'label' => '微博链接', 'type' => 'text', 'sort' => 1,
                'description' => '',
            ],
            [
                'group' => 'social', 'key' => 'social_github', 'value' => '',
                'label' => 'GitHub', 'type' => 'text', 'sort' => 2,
                'description' => '',
            ],
            [
                'group' => 'social', 'key' => 'social_linkedin', 'value' => '',
                'label' => '领英', 'type' => 'text', 'sort' => 3,
                'description' => '',
            ],

            // ========== SEO ==========
            [
                'group' => 'seo', 'key' => 'seo_title', 'value' => '云创科技 - 专注互联网技术创新',
                'label' => 'SEO标题', 'type' => 'text', 'sort' => 1,
                'description' => '浏览器标签页标题',
            ],
            [
                'group' => 'seo', 'key' => 'seo_keywords', 'value' => '云创科技,软件开发,数字化转型,互联网技术',
                'label' => 'SEO关键词', 'type' => 'textarea', 'sort' => 2,
                'description' => '逗号分隔',
            ],
            [
                'group' => 'seo', 'key' => 'seo_description', 'value' => '云创科技专注于互联网技术研发与软件开发，提供专业的数字化转型解决方案。',
                'label' => 'SEO描述', 'type' => 'textarea', 'sort' => 3,
                'description' => '',
            ],

            // ========== 主题配置 ==========
            [
                'group' => 'theme', 'key' => 'theme_primary_color', 'value' => '#2563eb',
                'label' => '主题主色', 'type' => 'text', 'sort' => 1,
                'description' => '十六进制颜色值，如 #2563eb',
            ],
            [
                'group' => 'theme', 'key' => 'theme_footer_text', 'value' => '© 2026 云创科技. All rights reserved.',
                'label' => '底部版权文字', 'type' => 'text', 'sort' => 2,
                'description' => '',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
