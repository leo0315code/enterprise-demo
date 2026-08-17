import { createInertiaApp } from '@inertiajs/vue3'
import { createApp, h } from 'vue'
import { route } from 'ziggy-js'

createInertiaApp({
    title: (title) => (title ? `${title} - 后台管理` : '后台管理'),
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            // 让模板里 this.$route 与 setup 注入 route 助手（依赖 @routes 注入的全局 Ziggy）
            .mount(el)
    },
})

// 暴露全局 route 助手给 Vue 组件（组合式 API 用 import { route } from 'ziggy-js'）
window.route = route
