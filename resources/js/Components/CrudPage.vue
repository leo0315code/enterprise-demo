<script setup>
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import Modal from '@/Components/Modal.vue'

/**
 * 通用 CRUD 页面骨架：列表 + 弹窗表单 + 删除。
 * 用法：各模块页面通过 <CrudPage :columns :form-fields :items :route-prefix :labels /> 复用。
 *
 * props:
 *  - items:       数组，列表数据（后端 index 传入）
 *  - columns:     表格列定义 [{ key, label, render? }]，render(item) 可返回 VNode/字符串
 *  - formFields:  弹窗表单字段定义（见下方），用于自动渲染表单
 *  - routePrefix: 路由前缀，如 'admin.categories'
 *  - labels:      { title, newTitle, editTitle, createMsg, updateMsg, empty }
 *  - primaryKey:  行主键字段名，默认 'id'
 *  - editKey:     编辑时用于拼 edit 路由的字段（slug 等），默认用 primaryKey
 */
const props = defineProps({
  items: { type: Array, default: () => [] },
  columns: { type: Array, required: true },
  formFields: { type: Array, required: true },
  routePrefix: { type: String, required: true },
  labels: { type: Object, default: () => ({}) },
  primaryKey: { type: String, default: 'id' },
  editKey: { type: String, default: '' },
})

const editId = ref(null)
const showModal = ref(false)
const submitting = ref(false)
const errors = ref({})
const toast = ref({ show: false, msg: '', ok: true })
let toastTimer

const form = useForm(
  Object.fromEntries(props.formFields.map((f) => [f.name, f.default ?? '']))
)

const isEdit = computed(() => editId.value !== null)

function fieldValue(field, item) {
  return item[field] ?? ''
}

function openNew() {
  errors.value = {}
  form.reset()
  props.formFields.forEach((f) => (form[f.name] = f.default ?? ''))
  editId.value = null
  showModal.value = true
}

function openEdit(item) {
  errors.value = {}
  props.formFields.forEach((f) => {
    form[f.name] = item[f.name] ?? f.default ?? ''
  })
  editId.value = item[props.editKey || props.primaryKey || 'id']
  showModal.value = true
}

function submit() {
  submitting.value = true
  errors.value = {}
  const method = isEdit.value ? 'put' : 'post'
  const url = isEdit.value
    ? route(`${props.routePrefix}.update`, editId.value)
    : route(`${props.routePrefix}.store`)

  form.submit(method, url, {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false
      flash(isEdit.value ? labelsText('updateMsg', '已更新') : labelsText('createMsg', '已创建'), true)
    },
    onError: (e) => {
      errors.value = e
    },
    onFinish: () => {
      submitting.value = false
    },
  })
}

function remove(item) {
  if (!confirm('确定删除？')) return
  router.delete(route(`${props.routePrefix}.destroy`, item[props.editKey || props.primaryKey || 'id']), {
    preserveScroll: true,
    onSuccess: () => flash('已删除', true),
  })
}

function labelsText(key, fallback) {
  return props.labels[key] ?? fallback
}

function flash(msg, ok) {
  toast.value = { show: true, msg, ok }
  clearTimeout(toastTimer)
  toastTimer = setTimeout(() => (toast.value.show = false), 2600)
}

function checkboxVal(name) {
  return form[name] ? true : false
}
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
      <h2 class="font-semibold text-gray-800">{{ labels.title || '' }}</h2>
      <button
        type="button"
        class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-4 py-2 rounded-lg text-sm font-medium"
        @click="openNew"
      >+ {{ labels.newTitle || '新增' }}</button>
    </div>

    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-500 text-left">
        <tr>
          <th v-for="col in columns" :key="col.key" class="px-6 py-3">{{ col.label }}</th>
          <th class="px-6 py-3 text-right">操作</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <tr v-for="item in items" :key="item[primaryKey]" class="transition-colors duration-300 hover:bg-slate-50">
          <td
            v-for="col in columns"
            :key="col.key"
            class="px-6 py-3"
            :class="col.tdClass || ''"
          >
            <template v-if="col.render">
              <component :is="col.render(item)" />
            </template>
            <template v-else>
              {{ fieldValue(col.key, item) }}
            </template>
          </td>
          <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
            <button type="button" class="text-blue-600 hover:underline" @click="openEdit(item)">编辑</button>
            <button type="button" class="text-red-500 hover:underline" @click="remove(item)">删除</button>
          </td>
        </tr>
        <tr v-if="!items.length">
          <td :colspan="columns.length + 1" class="px-6 py-8 text-center text-gray-400">
            {{ labels.empty || '暂无数据' }}
          </td>
        </tr>
      </tbody>
    </table>

    <Modal :open="showModal" :title="isEdit ? (labels.editTitle || '编辑') : (labels.newTitle || '新增')" :submitting="submitting" @close="showModal = false">
      <form id="modal-form" @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="field in formFields" :key="field.name" :class="field.wrapClass || ''">
          <label class="block text-sm font-medium text-gray-700 mb-1">{{ field.label }}</label>

          <input
            v-if="field.type === 'text' || field.type === 'number' || field.type === 'slug'"
            v-model="form[field.name]"
            :type="field.type === 'number' ? 'number' : 'text'"
            :placeholder="field.placeholder || ''"
            :required="field.required"
            class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
          />

          <textarea
            v-else-if="field.type === 'textarea'"
            v-model="form[field.name]"
            :rows="field.rows || 3"
            :placeholder="field.placeholder || ''"
            class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
          ></textarea>

          <label v-else-if="field.type === 'checkbox'" class="flex items-center gap-2 text-sm text-gray-700 mt-6">
            <input type="checkbox" v-model="form[field.name]" :value="1" class="rounded" /> {{ field.checkboxLabel || field.label }}
          </label>

          <p v-if="errors[field.name]" class="text-xs text-red-500 mt-1">{{ errors[field.name][0] }}</p>
        </div>
      </form>
    </Modal>

    <!-- 全局 Toast -->
    <Transition name="fade">
      <div
        v-if="toast.show"
        class="fixed bottom-6 right-6 z-[60] bg-gray-900 text-white text-sm px-4 py-3 rounded-xl shadow-lg flex items-center gap-2"
      >
        <span>{{ toast.ok ? '✓' : '⚠' }}</span>
        <span>{{ toast.msg }}</span>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
