<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AdminLayout })

const props = defineProps({
    stats: Object,
    weakPassword: Boolean,
    recentMessages: Array,
    recentPosts: Array,
    pageTitle: String,
})

function greeting() {
    const h = new Date().getHours()
    if (h < 6) return '夜深了'
    if (h < 12) return '上午好'
    if (h < 14) return '中午好'
    if (h < 18) return '下午好'
    return '晚上好'
}

const cards = [
    { label: '产品服务', value: props.stats.products, route: 'admin.products.index', color: 'bg-blue-500' },
    { label: '新闻文章', value: props.stats.posts, route: 'admin.posts.index', color: 'bg-emerald-500' },
    { label: '留言(未读)', value: props.stats.unread + '/' + props.stats.messages, route: 'admin.messages.index', color: 'bg-amber-500' },
    { label: '产品分类', value: props.stats.product_categories, route: 'admin.categories.index', color: 'bg-purple-500' },
    { label: '文章分类', value: props.stats.post_categories, route: 'admin.post-categories.index', color: 'bg-rose-500' },
]
</script>

<template>
    <div>
        <div v-if="weakPassword" class="mb-6 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg">
            检测到仍在使用默认密码 <b>admin123</b>，存在安全风险，请尽快
            <Link :href="route('admin.profile.password')" class="underline font-medium">修改密码</Link>。
        </div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ greeting() }}，{{ $page.props.auth.user.name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ new Date().toLocaleDateString('zh-CN', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            <Link v-for="c in cards" :key="c.label" :href="route(c.route)"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div :class="c.color" class="w-9 h-9 rounded-lg mb-3"></div>
                <div class="text-2xl font-bold text-gray-800">{{ c.value }}</div>
                <div class="text-sm text-gray-500 mt-1">{{ c.label }}</div>
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">最新留言</h3>
                <ul class="space-y-3">
                    <li v-for="m in recentMessages" :key="m.id" class="text-sm border-b border-gray-50 pb-2">
                        <span class="font-medium text-gray-800">{{ m.name }}</span>
                        <span class="text-gray-400 text-xs ml-2">{{ m.created_at }}</span>
                        <p class="text-gray-600 mt-1 truncate">{{ m.message }}</p>
                    </li>
                    <li v-if="!recentMessages.length" class="text-sm text-gray-400">暂无留言</li>
                </ul>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">最新文章</h3>
                <ul class="space-y-3">
                    <li v-for="p in recentPosts" :key="p.id" class="text-sm border-b border-gray-50 pb-2">
                        <Link :href="route('admin.posts.edit', p.id)" class="font-medium text-gray-800 hover:underline">{{ p.title }}</Link>
                        <span class="text-gray-400 text-xs ml-2">{{ p.created_at }}</span>
                    </li>
                    <li v-if="!recentPosts.length" class="text-sm text-gray-400">暂无文章</li>
                </ul>
            </div>
        </div>
    </div>
</template>
