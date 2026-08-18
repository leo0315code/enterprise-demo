<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);
        $unread = ContactMessage::where('is_read', false)->count();
        return inertia('Messages', [
            'messages' => $messages,
            'unread' => $unread,
        ]);
    }

    /**
     * 仅返回表格局部，供删除后无刷新刷新列表（Blade 遗留兼容）。
     */
    public function rows()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.messages._table', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        if (request()->ajax() || request()->inertia()) {
            return response()->json(['message' => $message]);
        }
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        if (request()->ajax() || request()->inertia()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('admin.messages.index')->with('success', '留言已删除');
    }

    /**
     * 返回未读留言数，供前端实时刷新角标。
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => ContactMessage::where('is_read', false)->count(),
        ]);
    }

    /**
     * 导出所有留言为 CSV 文件
     */
    public function export()
    {
        $messages = ContactMessage::latest()->get();

        $csv = "姓名,邮箱,电话,主题,留言内容,是否已读,提交时间\n";
        foreach ($messages as $message) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $this->escapeCsv($message->name),
                $this->escapeCsv($message->email),
                $this->escapeCsv($message->phone ?? ''),
                $this->escapeCsv($message->subject ?? ''),
                $this->escapeCsv($message->message),
                $message->is_read ? '是' : '否',
                $message->created_at->format('Y-m-d H:i:s')
            );
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="messages-' . date('Y-m-d') . '.csv"',
        ]);
    }

    /**
     * CSV 字段转义：包含逗号、引号或换行时用双引号包裹
     */
    protected function escapeCsv(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        if (str_contains($value, ',') || str_contains($value, '"') || str_contains($value, "\n")) {
            return '"' . str_replace('"', '""', $value) . '"';
        }
        return $value;
    }
}
