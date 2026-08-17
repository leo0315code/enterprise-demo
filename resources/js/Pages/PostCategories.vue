<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import CrudPage from '@/Components/CrudPage.vue'

defineProps({
  categories: { type: Array, default: () => [] },
})

const columns = [
  { key: 'name', label: '名称' },
  { key: 'slug', label: 'Slug', render: (item) => `/${item.slug}`, tdClass: 'text-gray-400' },
]

const formFields = [
  { name: 'name', label: '分类名称', type: 'text', required: true },
  { name: 'slug', label: 'Slug (URL)', type: 'slug', placeholder: '留空自动生成' },
  { name: 'is_active', label: '启用', type: 'checkbox', checkboxLabel: '启用', default: true },
]

const labels = {
  title: '文章分类',
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
      route-prefix="admin.post-categories"
      route-key="slug"
      :quick-toggles="[{ field: 'is_active', label: '启用', offLabel: '已停用' }]"
      :labels="labels"
    />
  </AdminLayout>
</template>
