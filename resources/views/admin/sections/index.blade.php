@extends('layouts.admin')

@section('page_title', '首页板块管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="font-semibold text-gray-800">首页板块（按排序展示）</h2>
        <a href="{{ route('admin.sections.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">+ 新增板块</a>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-6 py-3">排序</th>
                <th class="px-6 py-3">类型</th>
                <th class="px-6 py-3">标题</th>
                <th class="px-6 py-3">状态</th>
                <th class="px-6 py-3 text-right">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($sections as $section)
            <tr>
                <td class="px-6 py-3 text-gray-400">{{ $section->sort }}</td>
                <td class="px-6 py-3"><span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs">{{ $section->type }}</span></td>
                <td class="px-6 py-3 font-medium text-gray-800">{{ $section->title ?: '-' }}</td>
                <td class="px-6 py-3">
                    @if($section->is_active)
                        <span class="text-green-600">● 显示</span>
                    @else
                        <span class="text-gray-400">○ 隐藏</span>
                    @endif
                </td>
                <td class="px-6 py-3 text-right space-x-2">
                    <a href="{{ route('admin.sections.edit', $section) }}" class="text-blue-600 hover:underline">编辑</a>
                    <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="inline" onsubmit="return confirm('确认删除该板块？');">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">删除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">暂无板块</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
