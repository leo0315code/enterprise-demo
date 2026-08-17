<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  messages: { type: Object, default: () => ({ data: [] }) },
  unread: { type: Number, default: 0 },
})

const showDetail = ref(false)
const current = ref(null)
const deleting = ref(false)

function openMessage(msg) {
  // 标记已读：访问 show 路由（JSON 返回 message）
  fetch(route('admin.messages.show', msg.id), {
    headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
  })
    .then((r) => r.json())
    .then((data) => {
      current.value = data.message
      showDetail.value = true
    })
}

function remove(msg) {
  if (!confirm('确定删除该留言？')) return
  router.delete(route('admin.messages.destroy', msg.id), {
    preserveScroll: true,
    onSuccess: () => {
      if (current.value && current.value.id === msg.id) showDetail.value = false
    },
  })
}
</script>

<template>
  <AdminLayout>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="font-semibold text-gray-800">留言管理</h2>
        <span class="text-sm text-gray-500">未读 <b class="text-blue-600">{{ unread }}</b> 条</span>
      </div>

      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-left">
          <tr>
            <th class="px-6 py-3">姓名</th>
            <th class="px-6 py-3">邮箱</th>
            <th class="px-6 py-3">主题</th>
            <th class="px-6 py-3">状态</th>
            <th class="px-6 py-3">时间</th>
            <th class="px-6 py-3 text-right">操作</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="m in messages.data" :key="m.id" class="transition-colors duration-300 hover:bg-slate-50 cursor-pointer" @click="openMessage(m)">
            <td class="px-6 py-3 font-medium text-gray-800">
              <span v-if="!m.is_read" class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-1"></span>{{ m.name }}
            </td>
            <td class="px-6 py-3 text-gray-500">{{ m.email }}</td>
            <td class="px-6 py-3">{{ m.subject || '-' }}</td>
            <td class="px-6 py-3">{{ m.is_read ? '已读' : '未读' }}</td>
            <td class="px-6 py-3 text-gray-400">{{ m.created_at }}</td>
            <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap" @click.stop>
              <button type="button" class="text-red-500 hover:underline" @click="remove(m)">删除</button>
            </td>
          </tr>
          <tr v-if="!messages.data.length">
            <td colspan="6" class="px-6 py-8 text-center text-gray-400">暂无留言</td>
          </tr>
        </tbody>
      </table>

      <div v-if="messages.links" class="px-6 py-4 flex items-center justify-between text-sm text-gray-500">
        <span>共 {{ messages.total }} 条</span>
        <div class="flex gap-1">
          <button
            v-for="link in messages.links"
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
    </div>

    <Modal :open="showDetail" title="留言详情" :submitting="false" @close="showDetail = false">
      <div v-if="current" class="space-y-3 text-sm">
        <div><span class="text-gray-500">姓名：</span>{{ current.name }}</div>
        <div><span class="text-gray-500">邮箱：</span>{{ current.email }}</div>
        <div><span class="text-gray-500">电话：</span>{{ current.phone || '-' }}</div>
        <div><span class="text-gray-500">主题：</span>{{ current.subject || '-' }}</div>
        <div class="border-t pt-3"><span class="text-gray-500">内容：</span>
          <p class="mt-1 whitespace-pre-wrap text-gray-700">{{ current.message }}</p>
        </div>
      </div>
    </Modal>
  </AdminLayout>
</template>
