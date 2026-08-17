<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const saved = ref(false)

function submit() {
  form.put(route('admin.profile.password.update'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      saved.value = true
      setTimeout(() => (saved.value = false), 2600)
    },
  })
}
</script>

<template>
  <AdminLayout>
    <div class="max-w-xl bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-semibold text-gray-800">修改密码</h2>
      </div>
      <form class="p-6 space-y-5" @submit.prevent="submit">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">当前密码</label>
          <input v-model="form.current_password" type="password" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" />
          <p v-if="form.errors.current_password" class="text-xs text-red-500 mt-1">{{ form.errors.current_password }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">新密码</label>
          <input v-model="form.password" type="password" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" />
          <p v-if="form.errors.password" class="text-xs text-red-500 mt-1">{{ form.errors.password }}</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">确认新密码</label>
          <input v-model="form.password_confirmation" type="password" class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none" />
        </div>

        <div class="flex justify-end items-center gap-4">
          <span v-if="saved" class="text-sm text-green-600">✓ 密码已修改</span>
          <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium disabled:opacity-60">
            {{ form.processing ? '保存中…' : '保存' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
