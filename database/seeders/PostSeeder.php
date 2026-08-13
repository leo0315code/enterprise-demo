<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $news = Category::where('type', 'post')->where('slug', 'news')->first();
        $insight = Category::where('type', 'post')->where('slug', 'insight')->first();
        $tech = Category::where('type', 'post')->where('slug', 'tech')->first();

        $posts = [
            [
                'title' => '云创科技荣获年度数字化转型服务商大奖',
                'summary' => '在今年的行业峰会上，云创科技凭借在数字化转型领域的卓越表现获此殊荣。',
                'content' => '<h2>荣誉时刻</h2><p>近日，在 2026 年度企业数字化峰会上，云创科技凭借多个标杆项目与成熟的产品体系，荣获"年度数字化转型服务商"大奖。</p><p>未来我们将持续深耕技术与服务，为客户创造更大价值。</p>',
                'cover' => 'https://placehold.co/96x96/f59e0b/ffffff?text=获奖',
                'author' => '市场部',
                'is_featured' => true,
                'is_active' => true,
                'sort' => 1,
                'published_at' => now()->subDays(2),
                'category_id' => $news?->id,
            ],
            [
                'title' => '《2026 企业上云趋势报告》正式发布',
                'summary' => '我们联合行业机构发布上云趋势报告，解读云原生与 AI 融合的最新动向。',
                'content' => '<h2>报告亮点</h2><p>本报告基于超过 300 家企业的调研数据，深入剖析云原生、多云管理与 AI 工程化趋势。</p><ul><li>云原生采纳率持续攀升</li><li>AI 与业务系统加速融合</li><li>成本治理成为新焦点</li></ul>',
                'cover' => 'https://placehold.co/96x96/3b82f6/ffffff?text=报告',
                'author' => '研究院',
                'is_featured' => true,
                'is_active' => true,
                'sort' => 2,
                'published_at' => now()->subDays(6),
                'category_id' => $insight?->id,
            ],
            [
                'title' => '从零构建可观测性体系：我们的实践之路',
                'summary' => '分享我们在日志、指标、链路追踪一体化建设中的踩坑与经验。',
                'content' => '<h2>背景</h2><p>随着微服务规模扩大，排查问题越来越难。我们逐步引入统一的日志、指标与链路追踪方案。</p><h2>实践要点</h2><ul><li>统一埋点与采样策略</li><li>告警降噪与根因分析</li><li>以 SLO 驱动稳定性建设</li></ul>',
                'cover' => 'https://placehold.co/96x96/8b5cf6/ffffff?text=技术',
                'author' => '技术团队',
                'is_featured' => false,
                'is_active' => true,
                'sort' => 3,
                'published_at' => now()->subDays(10),
                'category_id' => $tech?->id,
            ],
            [
                'title' => '云创科技与某零售集团达成战略合作',
                'summary' => '双方将围绕智慧零售中台展开深度合作，共建数字化标杆。',
                'content' => '<h2>合作展望</h2><p>本次合作将整合双方优势资源，在会员运营、智能供应链等方向共同探索。</p>',
                'cover' => 'https://placehold.co/96x96/10b981/ffffff?text=合作',
                'author' => '市场部',
                'is_featured' => false,
                'is_active' => true,
                'sort' => 4,
                'published_at' => now()->subDays(15),
                'category_id' => $news?->id,
            ],
        ];

        foreach ($posts as $post) {
            $post['slug'] = Str::slug($post['title']) ?: 'post-' . Str::random(6);
            Post::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
