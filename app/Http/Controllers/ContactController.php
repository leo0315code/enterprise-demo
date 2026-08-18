<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // honeypot：机器人填了隐藏字段则假装成功，不落库
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('success', '留言已提交，我们会尽快与您联系！');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = ContactMessage::create($validated);

        $this->notifyAdmin($message);

        return redirect()->back()->with('success', '留言已提交，我们会尽快与您联系！');
    }

    /**
     * 新留言邮件通知（发给站点配置的联系邮箱，发送失败不影响留言入库）
     */
    protected function notifyAdmin(ContactMessage $message): void
    {
        $to = setting('contact_email');
        if (! $to) {
            return;
        }

        try {
            Mail::to($to)->send(new ContactMessageReceived($message));
        } catch (\Throwable $e) {
            Log::error('留言通知邮件发送失败: '.$e->getMessage());
        }
    }
}
