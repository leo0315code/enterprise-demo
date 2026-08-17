@extends('layouts.admin')

@section('page_title', '首页板块管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="font-semibold text-gray-800">首页板块（按排序展示）</h2>
        <button type="button" onclick="CrudModal.open(null)"
                class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-4 py-2 rounded-lg text-sm font-medium">
            + 新增板块
        </button>
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
        <tbody id="crud-list-container" class="divide-y divide-gray-100">
            @include('admin.sections._rows', ['sections' => $sections])
        </tbody>
    </table>
</div>

@include('admin._modal')

@push('scripts')
<script>
CrudModal.init({
    storeUrl: '{{ route('admin.sections.store') }}',
    editUrl: '{{ route('admin.sections.edit', '__ID__') }}',
    tableUrl: '{{ route('admin.sections.rows') }}',
    titleNew: '新增板块',
    titleEdit: '编辑板块',
    msgCreate: '板块已创建',
    msgUpdate: '板块已更新',
    blankHtml: {!! json_encode(view('admin.sections._fields', ['section' => null])->render()) !!}
});
</script>
@endpush
@endsection
