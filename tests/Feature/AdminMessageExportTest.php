<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMessageExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_messages_as_csv(): void
    {
        ContactMessage::create([
            'name' => '张三',
            'email' => 'zhangsan@example.com',
            'phone' => '13800138000',
            'subject' => '产品咨询',
            'message' => '请问这款产品有优惠吗？',
            'is_read' => true,
        ]);
        ContactMessage::create([
            'name' => '李四',
            'email' => 'lisi@example.com',
            'message' => "多行\n留言内容",
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin())
            ->get('/manage/messages/export')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertHeader('Content-Disposition', 'attachment; filename="messages-'.date('Y-m-d').'.csv"');

        $content = $response->getContent();
        $this->assertStringContainsString('张三', $content);
        $this->assertStringContainsString('zhangsan@example.com', $content);
        $this->assertStringContainsString('13800138000', $content);
        $this->assertStringContainsString('产品咨询', $content);
        $this->assertStringContainsString('请问这款产品有优惠吗？', $content);
        $this->assertStringContainsString('是', $content); // 已读
        $this->assertStringContainsString('否', $content); // 未读
        $this->assertStringContainsString('李四', $content);
        // CSV 转义：多行内容会被包围在双引号中
        $this->assertStringContainsString('"多行', $content);
        $this->assertStringContainsString('留言内容"', $content);
    }

    private function admin()
    {
        return \App\Models\User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);
    }
}
