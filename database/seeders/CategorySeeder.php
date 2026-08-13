<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 产品服务分类
        $productCats = [
            ['name' => '软件开发', 'slug' => 'software', 'description' => '企业级软件定制开发', 'sort' => 1],
            ['name' => '云服务', 'slug' => 'cloud', 'description' => '云迁移、云运维与云原生', 'sort' => 2],
            ['name' => '解决方案', 'slug' => 'solution', 'description' => '行业数字化整体解决方案', 'sort' => 3],
        ];
        foreach ($productCats as $cat) {
            Category::updateOrCreate(
                ['type' => 'product', 'slug' => $cat['slug']],
                array_merge($cat, ['type' => 'product', 'is_active' => true])
            );
        }

        // 新闻文章分类
        $postCats = [
            ['name' => '公司动态', 'slug' => 'news', 'description' => '企业资讯与大事记', 'sort' => 1],
            ['name' => '行业洞察', 'slug' => 'insight', 'description' => '行业趋势与深度分析', 'sort' => 2],
            ['name' => '技术分享', 'slug' => 'tech', 'description' => '技术实践与经验沉淀', 'sort' => 3],
        ];
        foreach ($postCats as $cat) {
            Category::updateOrCreate(
                ['type' => 'post', 'slug' => $cat['slug']],
                array_merge($cat, ['type' => 'post', 'is_active' => true])
            );
        }
    }
}
