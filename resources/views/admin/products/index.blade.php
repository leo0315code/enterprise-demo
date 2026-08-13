@extends('layouts.admin')

@section('page_title', '产品服务管理')

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-blue-600 hover:underline">← 分类管理</a>
    <button type="button" onclick="CrudModal.open(null)"
            class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-4 py-2 rounded-lg text-sm font-medium">+ 新增产品</button>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="搜索产品…" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <select name="category_id" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">全部分类</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">全部状态</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>上架</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>下架</option>
            </select>
            <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">筛选</button>
            @if(request()->anyFilled(['q','category_id','status']))
                <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:underline">重置</a>
            @endif
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
        <tbody id="crud-list-container" class="divide-y divide-gray-100">
            @include('admin.products._table', ['products' => $products])
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $products->links() }}</div>
</div>

@include('admin._modal')

@push('scripts')
<script>
CrudModal.init({
    storeUrl: '{{ route('admin.products.store') }}',
    editUrl: '{{ route('admin.products.edit', '__ID__') }}',
    tableUrl: '{{ route('admin.products.rows') }}?' + new URLSearchParams(window.location.search),
    titleNew: '新增产品',
    titleEdit: '编辑产品',
    msgCreate: '产品已创建',
    msgUpdate: '产品已更新',
    afterRender(form) {
        const input = form.querySelector('#thumbnail');
        const img = form.querySelector('#thumbnail-preview');
        if (!input || !img) return;
        const sync = () => {
            const v = input.value.trim();
            if (v) { img.src = v; img.classList.remove('hidden'); } else { img.classList.add('hidden'); }
        };
        input.addEventListener('input', sync);
        img.addEventListener('error', () => img.classList.add('hidden'));
        sync();
    }
});
</script>
@endpush
