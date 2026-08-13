<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $software = Category::where('type', 'product')->where('slug', 'software')->first();
        $cloud = Category::where('type', 'product')->where('slug', 'cloud')->first();
        $solution = Category::where('type', 'product')->where('slug', 'solution')->first();

        $products = [
            [
                'title' => '企业 ERP 管理系统',
                'summary' => '覆盖财务、采购、库存、销售的一体化企业资源管理平台，支持多组织多账套。',
                'content' => '<h2>产品概述</h2><p>面向中大型企业的 ERP 管理系统，提供财务、供应链、生产制造、人力资源等模块，打通企业内外部数据流。</p><h2>核心能力</h2><ul><li>多组织、多账套协同</li><li>实时报表与经营看板</li><li>开放的 API 与第三方集成</li></ul>',
                'thumbnail' => 'https://placehold.co/96x96/3b82f6/ffffff?text=ERP',
                'is_featured' => true,
                'status' => 'active',
                'sort' => 1,
                'category_id' => $software?->id,
            ],
            [
                'title' => '云原生容器平台',
                'summary' => '基于 Kubernetes 的一站式容器管理与 DevOps 平台，助力业务快速上云。',
                'content' => '<h2>产品概述</h2><p>提供容器编排、服务网格、CI/CD、可观测性等全套能力，降低云原生落地门槛。</p><h2>核心能力</h2><ul><li>多集群统一纳管</li><li>一键灰度发布</li><li>全链路监控告警</li></ul>',
                'thumbnail' => 'https://placehold.co/96x96/8b5cf6/ffffff?text=K8s',
                'is_featured' => true,
                'status' => 'active',
                'sort' => 2,
                'category_id' => $cloud?->id,
            ],
            [
                'title' => '智慧零售解决方案',
                'summary' => '融合线上线下会员、商品、营销数据，打造全渠道数字化零售中台。',
                'content' => '<h2>产品概述</h2><p>面向连锁零售品牌，提供会员运营、智能选品、门店数字化等能力，提升经营效率与顾客体验。</p><h2>核心能力</h2><ul><li>全渠道会员打通</li><li>智能补货与选品</li><li>精准营销触达</li></ul>',
                'thumbnail' => 'https://placehold.co/96x96/f59e0b/ffffff?text=零售',
                'is_featured' => true,
                'status' => 'active',
                'sort' => 3,
                'category_id' => $solution?->id,
            ],
            [
                'title' => '数据中台',
                'summary' => '统一企业数据资产，提供采集、治理、建模、服务化的一站式能力。',
                'content' => '<h2>产品概述</h2><p>帮助企业沉淀数据资产，构建指标与标签体系，支撑业务分析与智能决策。</p>',
                'thumbnail' => 'https://placehold.co/96x96/10b981/ffffff?text=数据',
                'is_featured' => false,
                'status' => 'active',
                'sort' => 4,
                'category_id' => $cloud?->id,
            ],
            [
                'title' => '移动应用开发',
                'summary' => 'iOS / Android / 小程序多端覆盖的定制化 App 研发服务。',
                'content' => '<h2>产品概述</h2><p>从需求梳理、UI 设计到研发上线，提供端到端移动应用交付。</p>',
                'thumbnail' => 'https://placehold.co/96x96/ef4444/ffffff?text=APP',
                'is_featured' => false,
                'status' => 'inactive',
                'sort' => 5,
                'category_id' => $software?->id,
            ],
        ];

        foreach ($products as $p) {
            $p['slug'] = Str::slug($p['title']) ?: 'product-' . Str::random(6);
            Product::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
