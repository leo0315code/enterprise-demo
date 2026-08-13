<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => '关于我们',
                'slug' => 'about',
                'seo_title' => '关于我们 - 云创科技',
                'seo_description' => '了解云创科技的发展历程、企业文化和核心团队。',
                'content' => '<h2>公司简介</h2><p>云创科技成立于2015年，是一家专注互联网技术研发与数字化转型的科技企业。我们秉持"技术驱动商业"的理念，为众多行业客户提供定制化解决方案。</p><h2>企业文化</h2><p>创新、协作、务实、共赢，是我们始终坚持的价值观。</p>',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'title' => '联系我们',
                'slug' => 'contact',
                'seo_title' => '联系我们 - 云创科技',
                'seo_description' => '联系云创科技，获取专属解决方案。',
                'content' => '<p>欢迎通过以下方式与我们取得联系，我们将在第一时间回复您的咨询。</p>',
                'is_active' => true,
                'sort' => 2,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
