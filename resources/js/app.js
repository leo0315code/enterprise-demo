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
        const app = createApp({ render: () => h(App, props) })
        app.use(plugin)
        // 注册全局 route 助手，使所有模板里可直接使用 route() / route().current()
        // 依赖 @routes 向全局注入的 Ziggy 配置（globalThis.Ziggy）
        app.config.globalProperties.route = route
        app.mount(el)
    },
})
