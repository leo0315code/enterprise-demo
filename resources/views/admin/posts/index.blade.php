@extends('layouts.admin')

@section('page_title', '新闻文章管理')

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('admin.post-categories.index') }}" class="text-sm text-blue-600 hover:underline">← 文章分类</a>
    <button type="button" onclick="CrudModal.open(null)"
            class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-4 py-2 rounded-lg text-sm font-medium">+ 发布文章</button>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="搜索文章…" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <select name="category_id" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">全部分类</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">全部状态</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>已公开</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>草稿</option>
            </select>
            <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm">筛选</button>
            @if(request()->anyFilled(['q','category_id','status']))
                <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-500 hover:underline">重置</a>
            @endif
        </form>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-6 py-3">封面</th>
                <th class="px-6 py-3">标题</th>
                <th class="px-6 py-3">分类</th>
                <th class="px-6 py-3">发布时间</th>
                <th class="px-6 py-3">状态</th>
                <th class="px-6 py-3 text-right">操作</th>
            </tr>
        </thead>
        <tbody id="crud-list-container" class="divide-y divide-gray-100">
            @include('admin.posts._table', ['posts' => $posts])
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $posts->links() }}</div>
</div>

@include('admin._modal')

@push('scripts')
<script>
CrudModal.init({
    storeUrl: '{{ route('admin.posts.store') }}',
    editUrl: '{{ route('admin.posts.edit', '__ID__') }}',
    tableUrl: '{{ route('admin.posts.rows') }}?' + new URLSearchParams(window.location.search),
    titleNew: '发布文章',
    titleEdit: '编辑文章',
    msgCreate: '文章已发布',
    msgUpdate: '文章已更新',
    afterRender(form) {
        const input = form.querySelector('#cover');
        const img = form.querySelector('#cover-preview');
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
