@extends('layouts.admin')

@section('page_title', '仪表盘')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-sm text-gray-500">产品服务</div>
        <div class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['products'] }}</div>
        <a href="{{ route('admin.products.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">管理 →</a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-sm text-gray-500">新闻文章</div>
        <div class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['posts'] }}</div>
        <a href="{{ route('admin.posts.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">管理 →</a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-sm text-gray-500">留言总数</div>
        <div class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['messages'] }}</div>
        <a href="{{ route('admin.messages.index') }}" class="text-xs text-blue-600 hover:underline mt-1 inline-block">查看 →</a>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-sm text-gray-500">未读留言</div>
        <div class="text-3xl font-bold {{ $stats['unread'] ? 'text-red-500' : 'text-gray-800' }} mt-2">{{ $stats['unread'] }}</div>
        @if($stats['unread'])
            <a href="{{ route('admin.messages.index') }}" class="text-xs text-red-500 hover:underline mt-1 inline-block">立即处理 →</a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- 最近留言 -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800">最近留言</h2>
            <a href="{{ route('admin.messages.index') }}" class="text-xs text-blue-600 hover:underline">全部</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentMessages as $msg)
                <a href="{{ route('admin.messages.show', $msg) }}" class="flex items-center justify-between px-6 py-3 hover:bg-gray-50 {{ $msg->is_read ? '' : 'bg-blue-50/40' }}">
                    <div>
                        <div class="text-sm font-medium text-gray-800">{{ $msg->name }} <span class="text-gray-400 font-normal">{{ $msg->email }}</span></div>
                        <div class="text-xs text-gray-500 truncate w-64">{{ Str::limit($msg->message, 40) }}</div>
                    </div>
                    <span class="text-xs text-gray-400">{{ $msg->created_at->format('m-d') }}</span>
                </a>
            @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">暂无留言</div>
            @endforelse
        </div>
    </div>

    <!-- 最近文章 -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800">最近文章</h2>
            <a href="{{ route('admin.posts.index') }}" class="text-xs text-blue-600 hover:underline">全部</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentPosts as $post)
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="text-sm font-medium text-gray-800 truncate w-64">{{ $post->title }}</div>
                    <span class="text-xs text-gray-400">{{ $post->published_at?->format('m-d') ?? $post->created_at->format('m-d') }}</span>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">暂无文章</div>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h2 class="font-semibold text-gray-800 mb-4">快速开始</h2>
    <ul class="space-y-3 text-sm text-gray-600">
        <li class="flex items-center gap-2"><span class="text-blue-500">→</span> 在 <a href="{{ route('admin.settings.index') }}" class="text-blue-600 hover:underline">站点设置</a> 中完善企业名称、联系方式、Logo 等基础信息</li>
        <li class="flex items-center gap-2"><span class="text-blue-500">→</span> 在 <a href="{{ route('admin.sections.index') }}" class="text-blue-600 hover:underline">首页板块</a> 中配置首页展示内容</li>
        <li class="flex items-center gap-2"><span class="text-blue-500">→</span> 在 <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">产品服务</a> / <a href="{{ route('admin.posts.index') }}" class="text-blue-600 hover:underline">新闻文章</a> 中添加内容</li>
    </ul>
</div>
@endsection
