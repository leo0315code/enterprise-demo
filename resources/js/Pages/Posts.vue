<script setup>
import { h } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudPage from '@/Components/CrudPage.vue'

const props = defineProps({
  posts: { type: Object, default: () => ({ data: [] }) },
  categories: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
})

const catOptions = props.categories.map((c) => ({ value: c.id, label: c.name }))

const columns = [
  { key: 'title', label: '标题', tdClass: 'font-medium text-gray-800' },
  { key: 'category', label: '分类', render: (item) => item.category?.name || '-' },
  { key: 'author', label: '作者', tdClass: 'text-gray-500' },
  { key: 'published_at', label: '发布时间' },
  { key: 'is_active', label: '状态', render: (item) =>
      item.is_active
        ? h('span', { class: 'text-green-600' }, '● 已发布')
        : h('span', { class: 'text-gray-400' }, '○ 草稿') },
  { key: 'is_featured', label: '精选', render: (item) =>
      item.is_featured ? h('span', { class: 'text-amber-500' }, '★') : h('span', {}, '-') },
]

const formFields = [
  { name: 'title', label: '文章标题', type: 'text', required: true },
  { name: 'slug', label: 'Slug (URL)', type: 'slug', placeholder: '留空自动生成' },
  { name: 'category_id', label: '分类', type: 'select', options: catOptions },
  { name: 'author', label: '作者', type: 'text' },
  { name: 'published_at', label: '发布时间', type: 'text', placeholder: 'YYYY-MM-DD' },
  { name: 'cover', label: '封面 URL', type: 'text', wrapClass: 'md:col-span-2', placeholder: 'https://... 或上传' },
  { name: 'summary', label: '摘要', type: 'textarea', wrapClass: 'md:col-span-2', rows: 2 },
  { name: 'content', label: '正文（后续接入富文本）', type: 'textarea', wrapClass: 'md:col-span-2', rows: 5 },
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
    />
  </AdminLayout>
</template>
