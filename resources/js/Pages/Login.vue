<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const props = defineProps({
  errors: { type: Object, default: () => ({}) },
  siteName: { type: String, default: '企业官网' },
})

const form = useForm({
  login: '',
  password: '',
  remember: false,
})

const avatar = computed(() => {
  const v = form.login.trim()
  return v ? Array.from(v)[0] : (props.siteName ? Array.from(props.siteName)[0] : '?')
})

const shake = ref(false)

function submit() {
  shake.value = false
  form.post(route('admin.login.post'), {
    preserveScroll: true,
    onError: () => {
      shake.value = true
    },
  })
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center px-4 text-gray-900">
    <div class="w-full max-w-sm">
      <div class="text-center mb-8">
        <div
          class="mx-auto w-16 h-16 rounded-2xl bg-blue-500 shadow-lg flex items-center justify-center text-white text-2xl font-bold mb-4"
        >{{ avatar }}</div>
        <div class="text-white text-2xl font-bold">{{ siteName }}</div>
        <div class="text-slate-400 text-sm mt-1">企业官网后台管理系统</div>
      </div>

      <div
        class="bg-white rounded-2xl shadow-xl p-8 transition-transform"
        :class="shake ? 'animate-[shake_.5s]' : ''"
      >
        <div v-if="form.errors.login" class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
          {{ form.errors.login }}
        </div>

        <form class="space-y-4" @submit.prevent="submit">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">用户名 / 邮箱</label>
            <input
              v-model="form.login"
              type="text"
              required
              autofocus
              placeholder="请输入用户名或邮箱"
              class="w-full rounded-lg border-gray-300 border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">密码</label>
            <input
              v-model="form.password"
              type="password"
              required
              class="w-full rounded-lg border-gray-300 border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            />
          </div>
          <label class="flex items-center gap-2 text-sm text-gray-600">
            <input v-model="form.remember" type="checkbox" class="rounded" /> 记住我
          </label>
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium disabled:opacity-60"
          >{{ form.processing ? '登录中…' : '登录' }}</button>
        </form>
      </div>

      <div class="text-center mt-6">
        <a href="/" class="text-slate-400 text-sm hover:text-white">← 返回网站首页</a>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  15% { transform: translateX(-10px); }
  30% { transform: translateX(8px); }
  45% { transform: translateX(-6px); }
  60% { transform: translateX(4px); }
  75% { transform: translateX(-2px); }
}
</style>
