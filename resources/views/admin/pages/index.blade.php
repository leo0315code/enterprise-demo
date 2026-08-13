@extends('layouts.admin')

@section('page_title', '单页管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="font-semibold text-gray-800">单页列表</h2>
        <a href="{{ route('admin.pages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">+ 新增页面</a>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-6 py-3">标题</th>
                <th class="px-6 py-3">Slug</th>
                <th class="px-6 py-3">状态</th>
                <th class="px-6 py-3 text-right">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pages as $page)
            <tr>
                <td class="px-6 py-3 font-medium text-gray-800">{{ $page->title }}</td>
                <td class="px-6 py-3 text-gray-400">/{{ $page->slug }}</td>
                <td class="px-6 py-3">
                    @if($page->is_active)<span class="text-green-600">● 显示</span>@else<span class="text-gray-400">○ 隐藏</span>@endif
                </td>
                <td class="px-6 py-3 text-right space-x-2">
                    <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-600 hover:underline">编辑</a>
                    @unless(in_array($page->slug, ['about','contact']))
                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('确认删除？');">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">删除</button>
                    </form>
                    @endunless
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">暂无页面</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
