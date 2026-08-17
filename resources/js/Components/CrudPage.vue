<script setup>
import { ref, computed, h, isVNode, defineAsyncComponent } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import Modal from '@/Components/Modal.vue'

// 富文本编辑器（含 wangEditor ~700KB）按需异步加载，拆分出独立 chunk，
// 避免前台访客与后台首屏不必要地下载编辑器代码。
const RichText = defineAsyncComponent({
  loader: () => import('@/Components/RichText.vue'),
  loadingComponent: {
    template:
      '<div class="p-4 text-sm text-gray-400 border border-gray-300 rounded-lg">富文本编辑器加载中…</div>',
  },
  delay: 0,
})

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
 *  - filters:     可选筛选栏 [{ name, label, type:'text'|'select', options? }]，提交后 router.get 带 query 刷新
 *  - paginator:   可选分页对象（Inertia 的 paginator），含 links/meta
 *  - extraData:   弹窗表单额外隐藏数据（如 categories 下拉），以字段名 => [{value,label}] 形式
 *  - quickToggles:可选的列表快捷切换，数组 [{ field, label, values?, invertLabel? }]
 *                 在列表"操作"列渲染一键切换按钮，点击即走现有 update 接口（无需进表单）。
 *                 - 任意布尔字段(field 为布尔)：点亮=开启，灰=关闭，点击翻面。
 *                 - 带 values 的字段(如 status)：values=[开启值,关闭值]，点亮=values[0]。
 */
const props = defineProps({
  items: { type: Array, default: () => [] },
  columns: { type: Array, required: true },
  formFields: { type: Array, required: true },
  routePrefix: { type: String, required: true },
  labels: { type: Object, default: () => ({}) },
  primaryKey: { type: String, default: 'id' },
  editKey: { type: String, default: '' },
  routeKey: { type: String, default: 'id' },
  filters: { type: Array, default: () => [] },
  filterValues: { type: Object, default: () => ({}) },
  paginator: { type: Object, default: null },
  extraData: { type: Object, default: () => ({}) },
  quickToggles: { type: Array, default: () => [] },
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

// 筛选栏：初始值从当前页面 filterValues 回填
const filterForm = useForm(
  Object.fromEntries(
    props.filters.map((f) => [f.name, props.filterValues[f.name] ?? ''])
  )
)

function applyFilters() {
  router.get(route(`${props.routePrefix}.index`), filterForm.data(), {
    preserveScroll: true,
    preserveState: true,
  })
}

// 将列的 render(item) 返回值统一规范为合法 VNode。
// 页面里 render 约定可返回：字符串(纯文本，如 "/slug") 或 h() 生成的 VNode。
// 切记：不要直接把结果塞进 <component :is>（字符串会被当标签名，抛 InvalidCharacterError）。
function renderCell(col, item) {
  const out = col.render ? col.render(item) : fieldValue(col.key, item)
  if (isVNode(out)) return out
  return h('span', {}, out == null || out === '' ? '' : String(out))
}

function resetFilters() {
  props.filters.forEach((f) => (filterForm[f.name] = ''))
  applyFilters()
}

// 表单字段若为 select 且 options 来自 extraData，取其映射
function optionsFor(field) {
  if (field.options) return field.options
  if (props.extraData[field.name]) return props.extraData[field.name]
  return []
}

function fieldValue(field, item) {
  return item[field] ?? ''
}

// 单图上传目标（来自根模板 meta，复用 /manage/upload）
const uploadUrl = computed(() => {
  const el = document.querySelector('meta[name="upload-url"]')
  return el ? el.getAttribute('content') : route('admin.upload.image')
})

const uploading = ref({})

async function uploadImage(event, name) {
  const file = event.target.files?.[0]
  if (!file) return
  uploading.value = { ...uploading.value, [name]: true }
  try {
    const fd = new FormData()
    fd.append('wangeditor-uploaded-image', file)
    const res = await fetch(uploadUrl.value, {
      method: 'POST',
      body: fd,
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' },
      credentials: 'same-origin',
    })
    const data = await res.json()
    if (res.ok && data.errno === 0 && data.data?.url) {
      form[name] = data.data.url
    } else {
      window.alert(data.message || '上传失败')
    }
  } catch (e) {
    window.alert('上传失败：网络错误')
  } finally {
    uploading.value = { ...uploading.value, [name]: false }
    event.target.value = ''
  }
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
  editId.value = item[props.routeKey]
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
  router.delete(route(`${props.routePrefix}.destroy`, item[props.routeKey]), {
    preserveScroll: true,
    onSuccess: () => flash('已删除', true),
  })
}

// 列表快捷切换：复用现有 update 接口，仅翻转目标字段，不必进表单。
// 后端 update 把 title/name/status 等标为 required，因此 payload 必须带整行字段，
// 只改目标布尔位（或 status 值），其余保持原值即可。控制器已有 request()->ajax() 的 JSON 分支。
const busy = ref({})

function toggleState(item, toggle) {
  const key = `${item[props.routeKey]}:${toggle.field}`
  if (busy.value[key]) return
  busy.value = { ...busy.value, [key]: true }

  // 计算新值：带 values 的（如 status）点亮=values[0]，否则布尔翻面
  const current = item[toggle.field]
  const next = toggle.values
    ? (current !== toggle.values[0] ? toggle.values[0] : toggle.values[1])
    : !current

  // 组装完整字段 payload（基于列表行 item，缺失则以 form 默认兜底）
  const payload = {}
  props.formFields.forEach((f) => {
    let v = item[f.name]
    if (v === undefined || v === null) v = f.default ?? ''
    if (f.type === 'checkbox') v = v ? 1 : 0
    if (f.type === 'number') v = v === '' || v === null ? 0 : Number(v)
    payload[f.name] = v
  })
  payload[toggle.field] = toggle.values ? next : (next ? 1 : 0)

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
  fetch(route(`${props.routePrefix}.update`, item[props.routeKey]), {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-HTTP-Method-Override': 'PUT',
      'Accept': 'application/json',
    },
    credentials: 'same-origin',
    body: JSON.stringify(payload),
  })
    .then(async (res) => {
      const ok = res.ok
      if (ok) {
        item[toggle.field] = next
        flash(`${toggle.label}已${toggle.values ? (next === toggle.values[0] ? '开启' : '关闭') : (next ? '开启' : '关闭')}`, true)
      } else {
        flash('操作失败', false)
      }
      return res
    })
    .catch(() => flash('操作失败：网络错误', false))
    .finally(() => {
      busy.value = { ...busy.value, [key]: false }
    })
}

function labelsText(key, fallback) {
  return props.labels[key] ?? fallback
}

// 判断某 toggle 当前是否点亮：带 values 的比对 values[0]，否则按布尔判断
function isOn(item, toggle) {
  return toggle.values ? item[toggle.field] === toggle.values[0] : !!item[toggle.field]
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

    <!-- 筛选栏（可选） -->
    <div v-if="filters.length" class="px-6 py-4 border-b border-gray-100 bg-gray-50/60 flex flex-wrap gap-3 items-end">
      <div v-for="f in filters" :key="f.name" class="flex flex-col gap-1">
        <label class="text-xs text-gray-500">{{ f.label }}</label>
        <input
          v-if="f.type !== 'select'"
          v-model="filterForm[f.name]"
          type="text"
          :placeholder="f.placeholder || ''"
          class="rounded-lg border-gray-300 border px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 w-44"
        />
        <select
          v-else
          v-model="filterForm[f.name]"
          class="rounded-lg border-gray-300 border px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500 bg-white w-44"
        >
          <option value="">全部</option>
          <option v-for="opt in (f.options || [])" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
      </div>
      <button type="button" class="px-4 py-1.5 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700" @click="applyFilters">筛选</button>
      <button type="button" class="px-3 py-1.5 rounded-lg text-gray-600 text-sm hover:bg-gray-100" @click="resetFilters">重置</button>
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
              <component :is="() => renderCell(col, item)" />
            </template>
            <template v-else>
              {{ fieldValue(col.key, item) }}
            </template>
          </td>
          <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
            <!-- 列表快捷切换（无需进表单） -->
            <button
              v-for="toggle in quickToggles"
              :key="toggle.field"
              type="button"
              :disabled="busy[`${item[routeKey]}:${toggle.field}`]"
              class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium transition border"
              :class="isOn(item, toggle)
                ? 'bg-green-50 text-green-600 border-green-200 hover:bg-green-100'
                : 'bg-gray-50 text-gray-400 border-gray-200 hover:bg-gray-100'"
              @click="toggleState(item, toggle)"
            >
              <span class="text-[10px]">{{ isOn(item, toggle) ? '●' : '○' }}</span>
              {{ isOn(item, toggle) ? toggle.label : (toggle.offLabel || `未${toggle.label}`) }}
            </button>

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

    <!-- 分页（可选） -->
    <div v-if="paginator && paginator.links" class="px-6 py-4 flex items-center justify-between text-sm text-gray-500">
      <span>共 {{ paginator.total }} 条</span>
      <div class="flex gap-1">
        <button
          v-for="link in paginator.links"
          :key="link.label"
          type="button"
          :disabled="!link.url"
          v-html="link.label"
          class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm disabled:opacity-40"
          :class="link.active ? 'bg-blue-600 text-white border-blue-600' : 'hover:bg-gray-100'"
          @click="link.url && router.get(link.url)"
        ></button>
      </div>
    </div>

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

          <select
            v-else-if="field.type === 'select'"
            v-model="form[field.name]"
            class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white"
          >
            <option v-for="opt in optionsFor(field)" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>

          <div v-else-if="field.type === 'richtext'">
            <RichText v-model="form[field.name]" :placeholder="field.placeholder || '请输入内容…'" :upload-url="uploadUrl" />
          </div>

          <div v-else-if="field.type === 'image'">
            <div class="flex items-center gap-3">
              <input
                v-model="form[field.name]"
                type="text"
                :placeholder="field.placeholder || 'https://... 或上传'"
                class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
              />
              <label class="shrink-0 cursor-pointer inline-flex items-center gap-1 text-sm text-blue-600 border border-blue-200 bg-blue-50 px-3 py-2 rounded-lg hover:bg-blue-100 transition">
                <input type="file" accept="image/*" class="hidden" @change="uploadImage($event, field.name)" :disabled="uploading[field.name]" />
                {{ uploading[field.name] ? '上传中…' : '上传' }}
              </label>
              <button v-if="form[field.name]" type="button" class="shrink-0 text-xs text-red-500 hover:underline" @click="form[field.name] = ''">移除</button>
            </div>
            <img v-if="form[field.name]" :src="form[field.name]" alt="" class="mt-2 max-h-28 rounded-lg border border-gray-200 object-contain" />
          </div>

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
