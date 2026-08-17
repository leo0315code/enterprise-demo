<script setup>
import { h } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudPage from '@/Components/CrudPage.vue'

defineProps({
  pages: { type: Array, default: () => [] },
})

const columns = [
  { key: 'title', label: '标题' },
  { key: 'slug', label: 'Slug', render: (item) => `/${item.slug}`, tdClass: 'text-gray-400' },
  { key: 'seo_title', label: 'SEO 标题', tdClass: 'text-gray-500' },
  {
    key: 'is_active',
    label: '状态',
    render: (item) =>
      item.is_active
        ? h('span', { class: 'text-green-600' }, '● 启用')
        : h('span', { class: 'text-gray-400' }, '○ 停用'),
  },
]

const formFields = [
  { name: 'title', label: '标题', type: 'text', required: true },
  { name: 'slug', label: 'Slug (URL)', type: 'slug', required: true, placeholder: '如 about / contact' },
  { name: 'seo_title', label: 'SEO 标题', type: 'text' },
  { name: 'seo_description', label: 'SEO 描述', type: 'textarea', wrapClass: 'md:col-span-2', rows: 2 },
  { name: 'content', label: '正文', type: 'textarea', wrapClass: 'md:col-span-2', rows: 5 },
  { name: 'sort', label: '排序', type: 'number', default: 0 },
  { name: 'is_active', label: '启用', type: 'checkbox', checkboxLabel: '启用', default: true },
]

const labels = {
  title: '页面管理',
  newTitle: '新增页面',
  editTitle: '编辑页面',
  createMsg: '页面已创建',
  updateMsg: '页面已更新',
  empty: '暂无页面',
}
</script>

<template>
  <AdminLayout>
    <CrudPage
      :items="pages"
      :columns="columns"
      :form-fields="formFields"
      route-prefix="admin.pages"
      route-key="slug"
      :labels="labels"
    />
  </AdminLayout>
</template>
