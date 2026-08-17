@extends('layouts.admin')

@section('page_title', '留言管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="font-semibold text-gray-800">用户留言</h2>
        <span id="unread-badge" class="@if($unread) bg-red-100 text-red-600 @else hidden @endif text-xs px-2 py-1 rounded-full">{{ $unread }} 条未读</span>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
                <th class="px-6 py-3">状态</th>
                <th class="px-6 py-3">姓名</th>
                <th class="px-6 py-3">邮箱</th>
                <th class="px-6 py-3">主题</th>
                <th class="px-6 py-3">时间</th>
                <th class="px-6 py-3 text-right">操作</th>
            </tr>
        </thead>
        <tbody id="crud-list-container" class="divide-y divide-gray-100">
            @include('admin.messages._table', ['messages' => $messages])
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $messages->links() }}</div>
</div>

@include('admin._modal')

@push('scripts')
<script>
// 留言专用封装：查看走 CrudModal 只读模式，删除走 AJAX 局部刷新
window.MessageModal = (function () {
    function refresh() {
        fetch('{{ route('admin.messages.rows') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.ok ? r.text() : Promise.reject())
            .then(html => {
                const box = document.getElementById('crud-list-container');
                if (box) box.innerHTML = html;
            })
            .catch(() => {});
        // 同步未读角标
        fetch('{{ route('admin.messages.unread-count') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(data => {
                const badge = document.getElementById('unread-badge');
                if (!badge) return;
                if (data.count > 0) {
                    badge.textContent = data.count + ' 条未读';
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(() => {});
    }

    CrudModal.init({
        viewUrl: '{{ route('admin.messages.show', '__ID__') }}',
        titleView: '留言详情',
        tableContainer: 'crud-list-container'
    });

    return {
        view(id) { CrudModal.view(id); },
        remove(id) {
            if (!confirm('确认删除该留言？')) return;
            const fd = new FormData();
            fd.append('_method', 'DELETE');
            fetch('{{ route('admin.messages.destroy', '__ID__') }}'.replace('__ID__', id), {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then(async r => {
                if (r.ok || r.status === 302) {
                    // 退出只读模式弹窗
                    CrudModal.close();
                    refresh();
                    CrudModal.toast('留言已删除', true);
                } else {
                    CrudModal.toast('删除失败', false);
                }
            }).catch(() => CrudModal.toast('网络错误', false));
        }
    };
})();
</script>
@endpush
@endsection
