<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudPage from '@/Components/CrudPage.vue'

const props = defineProps({
  products: { type: Object, default: () => ({ data: [] }) },
  categories: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
})

const catOptions = props.categories.map((c) => ({ value: c.id, label: c.name }))

const columns = [
  { key: 'title', label: '名称', tdClass: 'font-medium text-gray-800' },
  { key: 'category', label: '分类', render: (item) => item.category?.name || '-' },
  { key: 'sort', label: '排序' },
]

const formFields = [
  { name: 'title', label: '产品名称', type: 'text', required: true },
  { name: 'slug', label: 'Slug (URL)', type: 'slug', placeholder: '留空自动生成' },
  { name: 'category_id', label: '分类', type: 'select', options: catOptions },
  { name: 'status', label: '状态', type: 'select', options: [
      { value: 'active', label: '在售' }, { value: 'inactive', label: '下架' },
    ], default: 'active' },
  { name: 'thumbnail', label: '缩略图', type: 'image', wrapClass: 'md:col-span-2', placeholder: 'https://... 或上传' },
  { name: 'summary', label: '摘要', type: 'textarea', wrapClass: 'md:col-span-2', rows: 2 },
  { name: 'content', label: '详情', type: 'richtext', wrapClass: 'md:col-span-2' },
  { name: 'sort', label: '排序', type: 'number', default: 0 },
  { name: 'is_featured', label: '设为精选', type: 'checkbox', checkboxLabel: '精选', default: false },
]

const filterDefs = [
  { name: 'q', label: '关键词', placeholder: '名称搜索' },
  { name: 'category_id', label: '分类', type: 'select', options: catOptions },
  { name: 'status', label: '状态', type: 'select', options: [
      { value: 'active', label: '在售' }, { value: 'inactive', label: '下架' },
    ] },
]

const labels = {
  title: '产品管理',
  newTitle: '新增产品',
  editTitle: '编辑产品',
  createMsg: '产品已创建',
  updateMsg: '产品已更新',
  empty: '暂无产品',
}
</script>

<template>
  <AdminLayout>
    <CrudPage
      :items="products.data"
      :columns="columns"
      :form-fields="formFields"
      route-prefix="admin.products"
      :labels="labels"
      :filters="filterDefs"
      :filter-values="filters"
      :paginator="products"
      :extra-data="{ category_id: catOptions }"
      :quick-toggles="[
        { field: 'status', label: '上架', offLabel: '已下架', values: ['active', 'inactive'] },
        { field: 'is_featured', label: '精选' },
      ]"
    />
  </AdminLayout>
</template>
