<script setup>
import { onMounted, onBeforeUnmount, ref, watch, shallowRef } from 'vue'
import { createEditor, createToolbar } from '@wangeditor/editor'
import '@wangeditor/editor/dist/css/style.css'

const props = defineProps({
  modelValue: { type: String, default: '' },
  placeholder: { type: String, default: '请输入内容…' },
  height: { type: String, default: '320px' },
  uploadUrl: { type: String, default: '' },
})
const emit = defineEmits(['update:modelValue'])

const editorRef = shallowRef(null)
const toolbarRef = ref(null)
const editorContainer = ref(null)

function csrfToken() {
  const el = document.querySelector('meta[name="csrf-token"]')
  return el ? el.getAttribute('content') : ''
}

function buildEditor() {
  if (editorRef.value) return
  const editor = createEditor({
    selector: editorContainer.value,
    html: props.modelValue || '',
    config: {
      placeholder: props.placeholder,
      MENU_CONF: {
        uploadImage: {
          server: props.uploadUrl || '/manage/upload',
          fieldName: 'wangeditor-uploaded-image',
          // 自定义插入逻辑，兼容后端 { errno:0, data:{ url } } 约定
          customInsert(res, insertFn) {
            if (res && res.errno === 0 && res.data && res.data.url) {
              insertFn(res.data.url)
            } else {
              const msg = (res && (res.message || res.msg)) || '图片上传失败'
              window.alert(msg)
            }
          },
          // Laravel 需要 CSRF；用自定义上传头携带
          headers: { 'X-CSRF-TOKEN': csrfToken() },
          withCredentials: true,
          maxFileSize: 10 * 1024 * 1024,
          allowedFileTypes: ['image/*'],
        },
      },
      onCreated(editor) {
        editorRef.value = editor
      },
      onChange(editor) {
        emit('update:modelValue', editor.getHtml())
      },
    },
    mode: 'default',
  })

  createToolbar({
    editor,
    selector: toolbarRef.value,
    config: {},
    mode: 'default',
  })
}

onMounted(() => {
  buildEditor()
})

onBeforeUnmount(() => {
  const editor = editorRef.value
  if (editor == null) return
  editor.destroy()
  editorRef.value = null
})

// 外部值变化（打开弹窗回填、切换编辑项）时同步到编辑器
watch(
  () => props.modelValue,
  (val) => {
    const editor = editorRef.value
    if (editor && val !== editor.getHtml()) {
      editor.setHtml(val || '')
    }
  }
)
</script>

<template>
  <div class="rich-text-wrapper border border-gray-300 rounded-lg overflow-hidden">
    <div ref="toolbarRef" class="border-b border-gray-200 bg-gray-50"></div>
    <div ref="editorContainer" :style="{ height, overflowY: 'auto' }"></div>
  </div>
</template>
