<script setup>
import { watch } from 'vue'

const props = defineProps({
  open: { type: Boolean, default: false },
  title: { type: String, default: '' },
  submitting: { type: Boolean, default: false },
})
const emit = defineEmits(['close'])

// 打开弹窗时锁定背景滚动
watch(() => props.open, (val) => {
  document.body.style.overflow = val ? 'hidden' : ''
})
</script>

<template>
  <Transition name="fade">
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/40" @click="emit('close')"></div>
      <div
        class="relative w-full max-w-3xl max-h-[90vh] flex flex-col bg-white rounded-2xl shadow-2xl transition-all"
      >
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
          <h3 class="text-lg font-semibold text-gray-800">{{ title }}</h3>
          <button
            type="button"
            class="text-gray-400 hover:text-gray-700 text-2xl leading-none"
            @click="emit('close')"
          >&times;</button>
        </div>

        <div class="p-6 flex-1 min-h-0 overflow-y-auto">
          <slot />
        </div>

        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
          <slot name="footer">
            <button
              type="button"
              class="px-5 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition"
              @click="emit('close')"
            >取消</button>
            <button
              type="submit"
              form="modal-form"
              :disabled="submitting"
              class="bg-blue-600 hover:bg-blue-700 active:scale-95 transition text-white px-6 py-2.5 rounded-lg font-medium disabled:opacity-60 disabled:cursor-not-allowed"
            >{{ submitting ? '保存中…' : '保存' }}</button>
          </slot>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
