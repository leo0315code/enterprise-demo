@extends('layouts.admin')

@section('page_title', '留言详情')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <a href="{{ route('admin.messages.index') }}" class="text-blue-600 hover:underline text-sm">← 返回留言列表</a>

    <div class="mt-4 grid grid-cols-1 gap-4 text-sm">
        <div class="flex gap-3"><span class="text-gray-500 w-20">姓名：</span><span class="font-medium text-gray-800">{{ $message->name }}</span></div>
        <div class="flex gap-3"><span class="text-gray-500 w-20">邮箱：</span><a href="mailto:{{ $message->email }}" class="text-primary hover:underline">{{ $message->email }}</a></div>
        @if($message->phone)
            <div class="flex gap-3"><span class="text-gray-500 w-20">电话：</span><span class="text-gray-800">{{ $message->phone }}</span></div>
        @endif
        @if($message->subject)
            <div class="flex gap-3"><span class="text-gray-500 w-20">主题：</span><span class="text-gray-800">{{ $message->subject }}</span></div>
        @endif
        <div class="flex gap-3"><span class="text-gray-500 w-20">时间：</span><span class="text-gray-500">{{ $message->created_at->format('Y-m-d H:i') }}</span></div>
    </div>

    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <div class="text-gray-500 text-sm mb-2">留言内容：</div>
        <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="mailto:{{ $message->email }}" class="bg-primary text-white px-5 py-2.5 rounded-lg font-medium hover:opacity-90 transition">回复邮件</a>
        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('确认删除该留言？');">
            @csrf @method('DELETE')
            <button class="px-5 py-2.5 rounded-lg text-red-500 hover:bg-red-50">删除</button>
        </form>
    </div>
</div>
@endsection
