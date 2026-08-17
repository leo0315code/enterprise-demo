<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudPage from '@/Components/CrudPage.vue'

const props = defineProps({
  posts: { type: Object, default: () => ({ data: [] }) },
  categories: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
})

const catOptions = props.categories.map((c) => ({ value: c.id, label: c.name }))

// 把数据库 ISO 时间（如 2026-08-11T09:48:29.000000Z）格式化为 YYYY-MM-DD HH:mm（本地时区）
function formatDate(v) {
  if (!v) return '-'
  const d = new Date(v)
  if (isNaN(d.getTime())) return v
  const pad = (n) => String(n).padStart(2, '0')
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}`
}

const columns = [
  { key: 'title', label: '标题', tdClass: 'font-medium text-gray-800' },
  { key: 'category', label: '分类', render: (item) => item.category?.name || '-' },
  { key: 'author', label: '作者', tdClass: 'text-gray-500' },
  { key: 'published_at', label: '发布时间', render: (item) => formatDate(item.published_at), tdClass: 'text-gray-500' },
]

const formFields = [
  { name: 'title', label: '文章标题', type: 'text', required: true },
  { name: 'slug', label: 'Slug (URL)', type: 'slug', placeholder: '留空自动生成' },
  { name: 'category_id', label: '分类', type: 'select', options: catOptions },
  { name: 'author', label: '作者', type: 'text' },
  { name: 'published_at', label: '发布时间', type: 'text', placeholder: 'YYYY-MM-DD' },
  { name: 'cover', label: '封面', type: 'image', wrapClass: 'md:col-span-2', placeholder: 'https://... 或上传' },
  { name: 'summary', label: '摘要', type: 'textarea', wrapClass: 'md:col-span-2', rows: 2 },
  { name: 'content', label: '正文', type: 'richtext', wrapClass: 'md:col-span-2' },
  { name: 'is_featured', label: '设为精选', type: 'checkbox', checkboxLabel: '精选', default: false },
  { name: 'is_active', label: '发布', type: 'checkbox', checkboxLabel: '立即发布', default: true },
]

const filterDefs = [
  { name: 'q', label: '关键词', placeholder: '标题搜索' },
  { name: 'category_id', label: '分类', type: 'select', options: catOptions },
  { name: 'status', label: '状态', type: 'select', options: [
      { value: 'active', label: '已发布' }, { value: 'inactive', label: '草稿' },
    ] },
]

const labels = {
  title: '文章管理',
  newTitle: '新增文章',
  editTitle: '编辑文章',
  createMsg: '文章已发布',
  updateMsg: '文章已更新',
  empty: '暂无文章',
}
</script>

<template>
  <AdminLayout>
    <CrudPage
      :items="posts.data"
      :columns="columns"
      :form-fields="formFields"
      route-prefix="admin.posts"
      route-key="slug"
      :labels="labels"
      :filters="filterDefs"
      :filter-values="filters"
      :paginator="posts"
      :extra-data="{ category_id: catOptions }"
      :quick-toggles="[
        { field: 'is_active', label: '发布', offLabel: '草稿' },
        { field: 'is_featured', label: '精选' },
      ]"
    />
  </AdminLayout>
</template>
