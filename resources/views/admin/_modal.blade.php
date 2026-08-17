{{-- 通用弹窗容器：每个后台列表页 @include 一次即可，JS 全局复用 --}}
<div id="crud-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="modal-overlay absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-300"
         onclick="CrudModal.close()"></div>
    <div class="modal-card relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl opacity-0 scale-95 translate-y-4 transition-all duration-300 max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 id="crud-modal-title" class="text-lg font-semibold text-gray-800">编辑</h3>
            <button type="button" onclick="CrudModal.close()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div id="crud-modal-errors" class="hidden mx-6 mt-4 rounded-lg bg-red-50 text-red-600 text-sm p-3 space-y-1"></div>
        <form id="crud-form" class="p-6 flex-1 min-h-0 overflow-y-auto" onsubmit="event.preventDefault(); CrudModal.submit();">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="crud_id" value="">
            <div id="crud-form-body"></div>
            <div id="crud-modal-footer" class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="CrudModal.close()" class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition">取消</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-6 py-2.5 rounded-lg font-medium">保存</button>
            </div>
        </form>
    </div>
</div>

{{-- 全局 Toast --}}
<div id="crud-toast" class="fixed bottom-6 right-6 z-[60] hidden">
    <div class="bg-gray-900 text-white text-sm px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
        <span id="crud-toast-icon">✓</span>
        <span id="crud-toast-msg"></span>
    </div>
</div>

@push('scripts')
<script>
window.CrudModal = (function () {
    let cfg = {};
    function el(id) { return document.getElementById(id); }

    function show() {
        const modal = el('crud-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.querySelector('.modal-overlay').classList.remove('opacity-0');
            modal.querySelector('.modal-card').classList.remove('opacity-0', 'scale-95', 'translate-y-4');
        });
        document.body.style.overflow = 'hidden';
    }

    function hide() {
        const modal = el('crud-modal');
        modal.querySelector('.modal-overlay').classList.add('opacity-0');
        modal.querySelector('.modal-card').classList.add('opacity-0', 'scale-95', 'translate-y-4');
        document.body.style.overflow = '';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function setBody(html) {
        el('crud-form-body').innerHTML = html;
        if (typeof cfg.afterRender === 'function') {
            cfg.afterRender(el('crud-form'));
        }
    }

    function open(id) {
        const errBox = el('crud-modal-errors');
        errBox.classList.add('hidden');
        errBox.innerHTML = '';
        const form = el('crud-form');
        form.reset();
        form.querySelector('[name="_method"]').value = 'POST';
        form.querySelector('[name="crud_id"]').value = '';

        // 只读查看模式：隐藏保存/取消，使用详情片段
        if (typeof id === 'string' && id.startsWith('view:')) {
            const realId = id.slice(5);
            el('crud-modal-title').textContent = cfg.titleView || '详情';
            el('crud-modal-footer').classList.add('hidden');
            form.classList.add('crud-readonly');
            setBody(cfg.blankHtml || '<div class="py-8 text-center text-gray-400">加载中…</div>');
            if (cfg.viewUrl) {
                fetch(cfg.viewUrl.replace('__ID__', realId), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.ok ? r.text() : Promise.reject())
                    .then(html => setBody(html))
                    .catch(() => toast('加载失败，请重试', false));
            }
        } else if (id) {
            el('crud-modal-errors');
            el('crud-modal-footer').classList.remove('hidden');
            form.classList.remove('crud-readonly');
            el('crud-modal-title').textContent = cfg.titleEdit;
            setBody(cfg.blankHtml);
            fetch(cfg.editUrl.replace('__ID__', id), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.ok ? r.text() : Promise.reject())
                .then(html => setBody(html))
                .catch(() => toast('加载失败，请重试', false));
        } else {
            el('crud-modal-footer').classList.remove('hidden');
            form.classList.remove('crud-readonly');
            el('crud-modal-title').textContent = cfg.titleNew;
            setBody(cfg.blankHtml);
        }
        show();
    }

    function submit() {
        const id = el('crud-form').querySelector('[name="crud_id"]').value;
        const method = el('crud-form').querySelector('[name="_method"]').value;
        const url = id ? cfg.editUrl.replace('__ID__', id).replace(/\/edit$/, '') : cfg.storeUrl;
        const fd = new FormData(el('crud-form'));
        fd.delete('_method');
        fd.delete('crud_id');
        fd.append('_method', method);

        fetch(url, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(async r => {
            if (r.ok || r.status === 302) {
                if (cfg.tableUrl) {
                    const box = el(cfg.tableContainer || 'crud-list-container');
                    const fresh = await fetch(cfg.tableUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if (fresh.ok) box.innerHTML = await fresh.text();
                }
                hide();
                toast(id ? cfg.msgUpdate : cfg.msgCreate, true);
            } else if (r.status === 422) {
                const data = await r.json();
                showErrors(data.errors || {});
            } else {
                toast('保存失败', false);
            }
        }).catch(() => toast('网络错误', false));
    }

    function showErrors(errors) {
        const box = el('crud-modal-errors');
        const msgs = [];
        Object.keys(errors).forEach(k => msgs.push('<div>· ' + errors[k][0] + '</div>'));
        box.innerHTML = msgs.join('');
        box.classList.remove('hidden');
    }

    let toastTimer;
    function toast(msg, ok) {
        const box = el('crud-toast');
        el('crud-toast-msg').textContent = msg;
        el('crud-toast-icon').textContent = ok ? '✓' : '⚠';
        box.classList.remove('hidden');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => box.classList.add('hidden'), 2600);
    }

    return {
        init(config) { cfg = Object.assign({}, cfg, config); },
        open, close: hide, submit, toast,
        view(id) { open('view:' + id); }
    };
})();
</script>
@endpush
