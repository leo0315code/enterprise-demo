@extends('layouts.admin')

@section('page_title', '产品服务管理')

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-blue-600 hover:underline">← 分类管理</a>
    <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">+ 新增产品</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <form method="GET" class="flex gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="搜索产品…" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">搜索</button>
        </form>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-6 py-3">封面</th>
                <th class="px-6 py-3">名称</th>
                <th class="px-6 py-3">分类</th>
                <th class="px-6 py-3">状态</th>
                <th class="px-6 py-3 text-right">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
            <tr>
                <td class="px-6 py-3">
                    @if($product->thumbnail)
                        <img src="{{ $product->thumbnail }}" class="w-12 h-12 object-cover rounded">
                    @else
                        <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-xl">📦</div>
                    @endif
                </td>
                <td class="px-6 py-3 font-medium text-gray-800">
                    {{ $product->title }}
                    @if($product->is_featured)<span class="text-xs text-amber-600 ml-1">★推荐</span>@endif
                </td>
                <td class="px-6 py-3 text-gray-500">{{ $product->category->name ?? '未分类' }}</td>
                <td class="px-6 py-3">
                    @if($product->status == 'active')<span class="text-green-600">● 上架</span>@else<span class="text-gray-400">○ 下架</span>@endif
                </td>
                <td class="px-6 py-3 text-right space-x-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">编辑</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('确认删除？');">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">删除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">暂无产品，<a href="{{ route('admin.products.create') }}" class="text-blue-600">去新增</a></td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
