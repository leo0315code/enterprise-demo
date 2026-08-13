@extends('layouts.admin')

@section('page_title', '新闻文章管理')

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('admin.post-categories.index') }}" class="text-sm text-blue-600 hover:underline">← 文章分类</a>
    <a href="{{ route('admin.posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">+ 发布文章</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <form method="GET" class="flex gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="搜索文章…" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">搜索</button>
        </form>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-6 py-3">封面</th>
                <th class="px-6 py-3">标题</th>
                <th class="px-6 py-3">分类</th>
                <th class="px-6 py-3">发布时间</th>
                <th class="px-6 py-3 text-right">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($posts as $post)
            <tr>
                <td class="px-6 py-3">
                    @if($post->cover)
                        <img src="{{ $post->cover }}" class="w-12 h-12 object-cover rounded">
                    @else
                        <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-xl">📰</div>
                    @endif
                </td>
                <td class="px-6 py-3 font-medium text-gray-800">
                    {{ $post->title }}
                    @if($post->is_featured)<span class="text-xs text-amber-600 ml-1">★头条</span>@endif
                </td>
                <td class="px-6 py-3 text-gray-500">{{ $post->category->name ?? '未分类' }}</td>
                <td class="px-6 py-3 text-gray-400">{{ $post->published_at?->format('Y-m-d') ?? '-' }}</td>
                <td class="px-6 py-3 text-right space-x-2">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-600 hover:underline">编辑</a>
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('确认删除？');">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">删除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">暂无文章，<a href="{{ route('admin.posts.create') }}" class="text-blue-600">去发布</a></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $posts->links() }}</div>
</div>
@endsection
