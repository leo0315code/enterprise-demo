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
        return view('admin.messages.index', compact('messages', 'unread'));
    }

    /**
     * 仅返回表格局部，供删除后无刷新刷新列表。
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
        if (request()->ajax()) {
            return view('admin.messages._detail', compact('message'));
        }
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        if (request()->ajax()) {
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
}
