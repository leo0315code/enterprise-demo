@extends('layouts.admin')

@section('page_title', '留言管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="font-semibold text-gray-800">用户留言</h2>
        @if($unread)
            <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">{{ $unread }} 条未读</span>
        @endif
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-6 py-3">状态</th>
                <th class="px-6 py-3">姓名</th>
                <th class="px-6 py-3">邮箱</th>
                <th class="px-6 py-3">主题</th>
                <th class="px-6 py-3">时间</th>
                <th class="px-6 py-3 text-right">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($messages as $message)
            <tr class="{{ $message->is_read ? '' : 'bg-blue-50/40' }}">
                <td class="px-6 py-3">
                    @if($message->is_read)<span class="text-gray-400">○ 已读</span>@else<span class="text-blue-600 font-medium">● 未读</span>@endif
                </td>
                <td class="px-6 py-3 font-medium text-gray-800">{{ $message->name }}</td>
                <td class="px-6 py-3 text-gray-500">{{ $message->email }}</td>
                <td class="px-6 py-3 text-gray-600">{{ $message->subject ?: '（无主题）' }}</td>
                <td class="px-6 py-3 text-gray-400">{{ $message->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-6 py-3 text-right space-x-2">
                    <a href="{{ route('admin.messages.show', $message) }}" class="text-blue-600 hover:underline">查看</a>
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('确认删除？');">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">删除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">暂无留言</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $messages->links() }}</div>
</div>
@endsection
