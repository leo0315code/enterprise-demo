<script setup>
import { h } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudPage from '@/Components/CrudPage.vue'

defineProps({
  sections: { type: Array, default: () => [] },
})

const typeOptions = [
  { value: 'hero', label: '主视觉' },
  { value: 'intro', label: '简介' },
  { value: 'features', label: '特色' },
  { value: 'products', label: '产品' },
  { value: 'news', label: '新闻' },
  { value: 'cta', label: '行动召唤' },
  { value: 'custom', label: '自定义' },
]

const columns = [
  { key: 'type', label: '类型', render: (item) => typeOptions.find((t) => t.value === item.type)?.label || item.type },
  { key: 'title', label: '标题', tdClass: 'font-medium text-gray-800' },
  { key: 'subtitle', label: '副标题', tdClass: 'text-gray-500' },
  { key: 'sort', label: '排序' },
]

const formFields = [
  {
    name: 'type',
    label: '类型',
    type: 'select',
    required: true,
    options: typeOptions,
  },
  { name: 'title', label: '标题', type: 'text' },
  { name: 'subtitle', label: '副标题', type: 'text' },
  { name: 'content', label: '正文', type: 'richtext', wrapClass: 'md:col-span-2' },
  { name: 'image', label: '图片 URL', type: 'text', wrapClass: 'md:col-span-2', placeholder: 'https://...' },
  { name: 'button_text', label: '按钮文字', type: 'text' },
  { name: 'button_link', label: '按钮链接', type: 'text' },
  { name: 'sort', label: '排序', type: 'number', default: 0 },
  { name: 'is_active', label: '启用', type: 'checkbox', checkboxLabel: '启用', default: true },
]

const labels = {
  title: '首页板块',
  newTitle: '新增板块',
  editTitle: '编辑板块',
  createMsg: '板块已创建',
  updateMsg: '板块已更新',
  empty: '暂无板块',
}
</script>

<template>
  <AdminLayout>
    <CrudPage
      :items="sections"
      :columns="columns"
      :form-fields="formFields"
      route-prefix="admin.sections"
      :quick-toggles="[{ field: 'is_active', label: '启用', offLabel: '已停用' }]"
      :labels="labels"
    />
  </AdminLayout>
</template>
