// 富文本编辑器封装：基于 wangEditor v5
// 用法：在 Blade 片段里放一个 .rich-editor 容器（含隐藏 textarea[name]），
//      本模块会把容器内 #<id>-toolbar / #<id>-editor 实例化为编辑器。
import { createEditor, createToolbar } from '@wangeditor/editor'
import '@wangeditor/editor/dist/css/style.css'

function csrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]')
    return el ? el.getAttribute('content') : ''
}

function uploadUrl() {
    const el = document.querySelector('meta[name="upload-url"]')
    return el ? el.getAttribute('content') : '/manage/upload'
}

function mount(container) {
    const textarea = container.querySelector('textarea')
    if (!textarea) return

    // 防止重复挂载（每次弹窗 open 会重建 DOM，所以都是新节点，无需 destroy）
    if (container.dataset.mounted === '1') return

    const id = textarea.getAttribute('data-rt-id') || textarea.name || ('rt' + Math.random().toString(36).slice(2))
    const toolbarEl = container.querySelector('#' + CSS.escape(id) + '-toolbar')
    const editorEl = container.querySelector('#' + CSS.escape(id) + '-editor')
    if (!toolbarEl || !editorEl) return

    const editorConfig = {
        placeholder: textarea.getAttribute('placeholder') || '请输入内容…',
        MENU_CONF: {
            uploadImage: {
                server: uploadUrl(),
                fieldName: 'wangeditor-uploaded-image',
                // wangEditor 使用 XMLHttpRequest，需在请求头带 CSRF
                headers: { 'X-CSRF-TOKEN': csrfToken() },
                // 解析返回数据结构：{ errno:0, data:{ url } }
                customInsert(res, insertFn) {
                    if (res && res.errno === 0 && res.data && res.data.url) {
                        insertFn(res.data.url)
                    } else {
                        console.error('图片上传失败', res)
                    }
                },
            },
        },
        onChange(editor) {
            // 实时同步回隐藏 textarea，保证提交时拿到 HTML
            textarea.value = editor.getHtml()
        },
    }

    // 初始化 HTML（编辑场景回填）
    const initialHtml = textarea.value || ''
    const editor = createEditor({
        selector: editorEl,
        html: initialHtml,
        config: editorConfig,
        mode: 'default',
    })
    createToolbar({
        editor,
        selector: toolbarEl,
        mode: 'default',
    })

    container.dataset.mounted = '1'
}

function refresh(root) {
    root = root || document
    const containers = root.querySelectorAll('.rich-editor')
    containers.forEach((c) => mount(c))
}

// 暴露到全局，供 CrudModal 的 setBody 之后调用
window.RichText = { refresh, mount }

// 非弹窗（普通页面）首次加载时扫描一次
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => refresh())
} else {
    refresh()
}
