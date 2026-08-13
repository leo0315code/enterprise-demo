@extends('layouts.admin')

@section('page_title', '文章分类管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-2xl">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-semibold text-gray-800">新闻文章分类</h2>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($categories as $category)
        <form action="{{ route('admin.post-categories.update', $category) }}" method="POST" class="flex items-center gap-3 px-6 py-3">
            @csrf @method('PUT')
            <input type="text" name="name" value="{{ $category->name }}" class="flex-1 rounded-lg border-gray-300 border px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <input type="text" name="slug" value="{{ $category->slug }}" class="w-32 rounded-lg border-gray-300 border px-3 py-1.5 text-sm text-gray-500 focus:ring-2 focus:ring-blue-500 outline-none">
            <label class="flex items-center gap-1 text-xs text-gray-600">
                <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="rounded">启
            </label>
            <button class="text-blue-600 text-sm hover:underline">保存</button>
            <form action="{{ route('admin.post-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('删除该分类？');">
                @csrf @method('DELETE')
                <button class="text-red-500 text-sm hover:underline">删</button>
            </form>
        </form>
        @empty
        <div class="px-6 py-8 text-center text-gray-400">暂无分类</div>
        @endforelse
    </div>

    <form action="{{ route('admin.post-categories.store') }}" method="POST" class="flex items-center gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
        @csrf
        <input type="text" name="name" placeholder="新分类名称" required class="flex-1 rounded-lg border-gray-300 border px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
        <input type="text" name="slug" placeholder="slug (可选)" class="w-32 rounded-lg border-gray-300 border px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-sm">+ 添加</button>
    </form>
</div>
@endsection
