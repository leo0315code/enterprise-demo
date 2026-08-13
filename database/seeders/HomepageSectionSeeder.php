<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'type' => 'hero',
                'title' => '创新驱动未来',
                'subtitle' => '专注互联网技术研发与数字化转型，为企业提供一站式解决方案',
                'content' => null,
                'image' => '',
                'button_text' => '了解我们',
                'button_link' => '/about',
                'extra' => null,
                'sort' => 1,
                'is_active' => true,
            ],
            [
                'type' => 'intro',
                'title' => '关于我们',
                'subtitle' => '以技术赋能商业，让创新触手可及',
                'content' => '<p>我们是一家专注于互联网技术研发的科技企业，深耕行业多年，服务超过 500 家企业客户，致力于通过数字化手段提升企业运营效率。</p>',
                'image' => '',
                'button_text' => '查看详情',
                'button_link' => '/about',
                'extra' => null,
                'sort' => 2,
                'is_active' => true,
            ],
            [
                'type' => 'features',
                'title' => '我们的核心优势',
                'subtitle' => '为什么选择我们',
                'content' => null,
                'image' => '',
                'button_text' => null,
                'button_link' => null,
                'extra' => [
                    ['icon' => '🚀', 'title' => '技术领先', 'desc' => '持续投入研发，掌握核心技术与行业前沿方案'],
                    ['icon' => '💡', 'title' => '创新理念', 'desc' => '以客户需求为导向，提供定制化创新解决方案'],
                    ['icon' => '🤝', 'title' => '专业服务', 'desc' => '资深团队全程陪跑，保障项目高效落地'],
                    ['icon' => '🛡️', 'title' => '安全稳定', 'desc' => '完善的安全体系，确保业务持续稳定运行'],
                ],
                'sort' => 3,
                'is_active' => true,
            ],
            [
                'type' => 'products',
                'title' => '核心产品与服务',
                'subtitle' => '为企业数字化转型提供可靠支撑',
                'content' => null,
                'image' => '',
                'button_text' => null,
                'button_link' => null,
                'extra' => null,
                'sort' => 4,
                'is_active' => true,
            ],
            [
                'type' => 'news',
                'title' => '最新动态',
                'subtitle' => '了解我们的产品进展与行业洞察',
                'content' => null,
                'image' => '',
                'button_text' => null,
                'button_link' => null,
                'extra' => null,
                'sort' => 5,
                'is_active' => true,
            ],
            [
                'type' => 'cta',
                'title' => '准备好开始合作了吗？',
                'subtitle' => '立即联系我们，获取专属方案',
                'content' => null,
                'image' => '',
                'button_text' => '免费咨询',
                'button_link' => '/contact',
                'extra' => null,
                'sort' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($sections as $section) {
            HomepageSection::create($section);
        }
    }
}
