@extends('layouts.admin')

@section('page_title', '分类管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="font-semibold text-gray-800">产品分类</h2>
        <button type="button" onclick="CrudModal.open(null)"
                class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-4 py-2 rounded-lg text-sm font-medium">+ 新增分类</button>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-6 py-3">名称</th>
                <th class="px-6 py-3">Slug</th>
                <th class="px-6 py-3">描述</th>
                <th class="px-6 py-3">状态</th>
                <th class="px-6 py-3 text-right">操作</th>
            </tr>
        </thead>
        <tbody id="crud-list-container" class="divide-y divide-gray-100">
            @include('admin.categories._table', ['categories' => $categories])
        </tbody>
    </table>
</div>

@include('admin._modal')

@push('scripts')
<script>
CrudModal.init({
    storeUrl: '{{ route('admin.categories.store') }}',
    editUrl: '{{ route('admin.categories.edit', '__ID__') }}',
    tableUrl: '{{ route('admin.categories.rows') }}',
    titleNew: '新增分类',
    titleEdit: '编辑分类',
    msgCreate: '分类已创建',
    msgUpdate: '分类已更新'
});
</script>
@endpush
@endsection
