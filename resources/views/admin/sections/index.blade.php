@extends('layouts.admin')

@section('page_title', '首页板块管理')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="font-semibold text-gray-800">首页板块（按排序展示）</h2>
        <button type="button" onclick="openSectionModal(null)"
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
        <tbody id="sections-tbody" class="divide-y divide-gray-100">
            @include('admin.sections._rows', ['sections' => $sections])
        </tbody>
    </table>
</div>

{{-- ============ 弹窗 ============ --}}
<div id="section-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    {{-- 遮罩（淡入） --}}
    <div class="modal-overlay absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-300"
         onclick="closeSectionModal()"></div>

    {{-- 弹窗卡片（缩放淡入） --}}
    <div class="modal-card relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl opacity-0 scale-95 translate-y-4 transition-all duration-300">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 id="section-modal-title" class="text-lg font-semibold text-gray-800">新增板块</h3>
            <button type="button" onclick="closeSectionModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>

        {{-- 错误汇总 --}}
        <div id="section-modal-errors" class="hidden mx-6 mt-4 rounded-lg bg-red-50 text-red-600 text-sm p-3"></div>

        <form id="section-form" class="p-6 max-h-[70vh] overflow-y-auto"
              onsubmit="event.preventDefault(); submitSectionForm();">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="section_id" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">板块类型</label>
                    <select name="type" required class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        @foreach(['hero'=>'Hero Banner','intro'=>'公司简介','features'=>'核心优势（卡片）','products'=>'推荐产品','news'=>'最新新闻','cta'=>'行动召唤','custom'=>'自定义内容'] as $val=>$label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">排序</label>
                    <input type="number" name="sort" value="0" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">标题</label>
                    <input type="text" name="title" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">副标题</label>
                    <input type="text" name="subtitle" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">按钮文字</label>
                    <input type="text" name="button_text" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">按钮链接</label>
                    <input type="text" name="button_link" placeholder="/about" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">图片 / 背景 URL</label>
                    <input type="text" name="image" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">正文内容（支持 HTML）</label>
                    <textarea name="content" rows="6" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">扩展配置（JSON，如卡片列表）</label>
                    <textarea name="extra" rows="4" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono"></textarea>
                    <p class="text-xs text-gray-400 mt-1">格式：[{"icon":"🚀","title":"标题","desc":"描述"}]</p>
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded">
                        在前台显示该板块
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeSectionModal()" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">取消</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-6 py-2.5 rounded-lg font-medium">保存</button>
            </div>
        </form>
    </div>
</div>

{{-- Toast 提示 --}}
<div id="section-toast" class="fixed bottom-6 right-6 z-[60] hidden">
    <div class="bg-gray-900 text-white text-sm px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
        <span id="section-toast-icon">✓</span>
        <span id="section-toast-msg"></span>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const modal = document.getElementById('section-modal');
    const overlay = modal.querySelector('.modal-overlay');
    const card = modal.querySelector('.modal-card');
    const form = document.getElementById('section-form');
    const titleEl = document.getElementById('section-modal-title');
    const errBox = document.getElementById('section-modal-errors');

    function showModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            card.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        });
        document.body.style.overflow = 'hidden';
    }

    function hideModal() {
        overlay.classList.add('opacity-0');
        card.classList.add('opacity-0', 'scale-95', 'translate-y-4');
        document.body.style.overflow = '';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    window.closeSectionModal = hideModal;

    window.openSectionModal = function (id) {
        errBox.classList.add('hidden');
        errBox.innerHTML = '';
        form.reset();
        form.querySelector('[name="_method"]').value = 'POST';
        form.querySelector('[name="section_id"]').value = '';
        titleEl.textContent = '新增板块';

        if (id) {
            titleEl.textContent = '编辑板块';
            form.querySelector('[name="_method"]').value = 'PUT';
            form.querySelector('[name="section_id"]').value = id;
            fetch('{{ route('admin.sections.edit', '__ID__') }}'.replace('__ID__', id), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.ok ? r.text() : Promise.reject())
            .then(html => {
                // 从返回的编辑视图片断中提取字段值（视图片段仅含表单字段）
                const tmp = document.createElement('div');
                tmp.innerHTML = html;
                const map = {};
                tmp.querySelectorAll('[name]').forEach(el => { map[el.name] = el; });
                ['type','sort','title','subtitle','button_text','button_link','image','content','extra'].forEach(name => {
                    const src = map[name];
                    const dst = form.querySelector('[name="'+name+'"]');
                    if (src && dst) dst.value = src.value || (src.tagName === 'TEXTAREA' ? src.textContent.trim() : '');
                });
                const chk = map['is_active'];
                const dstChk = form.querySelector('[name="is_active"]');
                if (dstChk) dstChk.checked = !!(chk && (chk.checked || chk.value === '1'));
            })
            .catch(() => toast('加载失败，请重试', false));
        }
        showModal();
    };

    window.submitSectionForm = function () {
        const id = form.querySelector('[name="section_id"]').value;
        const method = form.querySelector('[name="_method"]').value;
        const url = id
            ? '{{ route('admin.sections.update', '__ID__') }}'.replace('__ID__', id)
            : '{{ route('admin.sections.store') }}';

        const fd = new FormData(form);
        fd.delete('_method');
        fd.append('_method', method);

        fetch(url, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(async r => {
            if (r.ok) {
                // 刷新表格
                const rows = await fetch('{{ route('admin.sections.rows') }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('sections-tbody').innerHTML = await rows.text();
                hideModal();
                toast(id ? '板块已更新' : '板块已创建', true);
            } else if (r.status === 422) {
                const data = await r.json();
                showErrors(data.errors || {});
            } else {
                toast('保存失败', false);
            }
        })
        .catch(() => toast('网络错误', false));
    };

    function showErrors(errors) {
        const msgs = [];
        Object.keys(errors).forEach(k => { msgs.push(errors[k][0]); });
        errBox.innerHTML = msgs.map(m => '<div>· ' + m + '</div>').join('');
        errBox.classList.remove('hidden');
    }

    let toastTimer;
    function toast(msg, ok) {
        const box = document.getElementById('section-toast');
        document.getElementById('section-toast-msg').textContent = msg;
        document.getElementById('section-toast-icon').textContent = ok ? '✓' : '⚠';
        box.classList.remove('hidden');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => box.classList.add('hidden'), 2600);
    }

    // 编辑视图片段：把编辑页的表单字段返回（用于 fetch 填充）
    @if(request()->ajax() && request()->routeIs('admin.sections.edit'))
    @endif
})();
</script>
@endpush
@endsection
