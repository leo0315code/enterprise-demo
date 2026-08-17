<script setup>
import { reactive, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  groups: { type: Array, default: () => [] },
})

const form = useForm({
  settings: {},
})

// 初始化 settings 默认值
props.groups.forEach((g) => {
  g.items.forEach((item) => {
    form.settings[item.key] = item.value ?? ''
  })
})

const saved = ref(false)
let savedTimer

function submit() {
  form.put(route('admin.settings.update'), {
    preserveScroll: true,
    onSuccess: () => {
      saved.value = true
      clearTimeout(savedTimer)
      savedTimer = setTimeout(() => (saved.value = false), 2600)
    },
  })
}
</script>

<template>
  <AdminLayout>
    <form class="space-y-8" @submit.prevent="submit">
      <div v-for="g in groups" :key="g.key" class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
          <h2 class="font-semibold text-gray-800">{{ g.label }}</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div
            v-for="item in g.items"
            :key="item.key"
            :class="item.type === 'textarea' ? 'md:col-span-2' : 'md:col-span-1'"
          >
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ item.label }}
              <span v-if="item.description" class="text-xs font-normal text-gray-400 ml-1">{{ item.description }}</span>
            </label>

            <textarea
              v-if="item.type === 'textarea'"
              v-model="form.settings[item.key]"
              rows="3"
              class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
            ></textarea>

            <select
              v-else-if="item.type === 'switch'"
              v-model="form.settings[item.key]"
              class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white"
            >
              <option value="1">开启</option>
              <option value="0">关闭</option>
            </select>

            <input
              v-else
              v-model="form.settings[item.key]"
              type="text"
              :placeholder="item.description || ''"
              class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
            />
          </div>
        </div>
      </div>

      <div class="flex justify-end items-center gap-4">
        <span v-if="saved" class="text-sm text-green-600">✓ 已保存</span>
        <button
          type="submit"
          :disabled="form.processing"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm disabled:opacity-60"
        >{{ form.processing ? '保存中…' : '保存设置' }}</button>
      </div>
    </form>
  </AdminLayout>
</template>
