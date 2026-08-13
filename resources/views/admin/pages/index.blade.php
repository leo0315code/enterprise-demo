@extends('layouts.admin')

@section('page_title', '单页管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="font-semibold text-gray-800">单页列表</h2>
        <button type="button" onclick="CrudModal.open(null)"
                class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-4 py-2 rounded-lg text-sm font-medium">+ 新增页面</button>
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
        <tbody id="crud-list-container" class="divide-y divide-gray-100">
            @include('admin.pages._table', ['pages' => $pages])
        </tbody>
    </table>
</div>

@include('admin._modal')

@push('scripts')
<script>
CrudModal.init({
    storeUrl: '{{ route('admin.pages.store') }}',
    editUrl: '{{ route('admin.pages.edit', '__ID__') }}',
    tableUrl: '{{ route('admin.pages.rows') }}',
    titleNew: '新增页面',
    titleEdit: '编辑页面',
    msgCreate: '页面已创建',
    msgUpdate: '页面已更新',
    blankHtml: ``
});
</script>
@endpush
@endsection
