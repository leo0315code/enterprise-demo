<script setup>
import { h } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudPage from '@/Components/CrudPage.vue'

defineProps({
  categories: { type: Array, default: () => [] },
})

const columns = [
  { key: 'name', label: '名称' },
  {
    key: 'slug',
    label: 'Slug',
    render: (item) => `/${item.slug}`,
    tdClass: 'text-gray-400',
  },
  {
    key: 'description',
    label: '描述',
    tdClass: 'text-gray-500',
  },
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
  { name: 'name', label: '分类名称', type: 'text', required: true },
  { name: 'slug', label: 'Slug (URL)', type: 'slug', placeholder: '留空自动生成' },
  { name: 'sort', label: '排序', type: 'number', default: 0 },
  { name: 'is_active', label: '启用', type: 'checkbox', checkboxLabel: '启用', default: true },
  { name: 'description', label: '描述', type: 'textarea', wrapClass: 'md:col-span-2', rows: 3 },
]

const labels = {
  title: '产品分类',
  newTitle: '新增分类',
  editTitle: '编辑分类',
  createMsg: '分类已创建',
  updateMsg: '分类已更新',
  empty: '暂无分类',
}
</script>

<template>
  <AdminLayout>
    <CrudPage
      :items="categories"
      :columns="columns"
      :form-fields="formFields"
      route-prefix="admin.categories"
      :labels="labels"
    />
  </AdminLayout>
</template>
