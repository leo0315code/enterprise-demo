<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user || { name: '管理员', email: '' })

const nav = [
    { label: '仪表盘', route: 'admin.dashboard', icon: '📊' },
    { section: '内容配置' },
    { label: '站点设置', route: 'admin.settings.index', icon: '⚙️' },
    { label: '首页板块', route: 'admin.sections.index', icon: '🏠' },
    { label: '单页管理', route: 'admin.pages.index', icon: '📄' },
    { section: '内容管理' },
    { label: '产品服务', route: 'admin.products.index', icon: '📦' },
    { label: '产品分类', route: 'admin.categories.index', icon: '🏷️' },
    { label: '新闻文章', route: 'admin.posts.index', icon: '📰' },
    { label: '文章分类', route: 'admin.post-categories.index', icon: '🗂️' },
    { label: '留言管理', route: 'admin.messages.index', icon: '✉️' },
]

const userMenuOpen = ref(false)
const menuRoot = ref(null)

// 侧边栏（移动端抽屉）开合状态
const sidebarOpen = ref(false)
function toggleSidebar() { sidebarOpen.value = !sidebarOpen.value }
function closeSidebar() { sidebarOpen.value = false }

function toggleMenu() { userMenuOpen.value = !userMenuOpen.value }
function closeMenu() { userMenuOpen.value = false }
function onDocClick(e) {
    if (menuRoot.value && !menuRoot.value.contains(e.target)) closeMenu()
}
function onKey(e) {
    if (e.key === 'Escape') { closeMenu(); closeSidebar() }
}
function onResize() {
    // 回到桌面尺寸时收起抽屉，避免状态残留
    if (window.innerWidth >= 1024) sidebarOpen.value = false
}

onMounted(() => {
    document.addEventListener('click', onDocClick)
    document.addEventListener('keydown', onKey)
    window.addEventListener('resize', onResize)
})
onUnmounted(() => {
    document.removeEventListener('click', onDocClick)
    document.removeEventListener('keydown', onKey)
    window.removeEventListener('resize', onResize)
})

const firstName = computed(() => (user.value.name || '管').charAt(0))
</script>

<template>
    <div class="min-h-full flex">
        <!-- 侧边栏 -->
        <aside class="w-64 bg-slate-800 text-gray-300 flex flex-col fixed h-full z-40 transition-transform duration-200 ease-in-out -translate-x-full lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen }">
            <div class="h-16 flex items-center px-6 text-white font-bold text-lg border-b border-slate-700">
                {{ page.props.siteName || '企业官网' }}
                <span class="text-xs ml-2 text-slate-400 font-normal">后台</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <template v-for="(item, i) in nav" :key="i">
                    <div v-if="item.section" class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-slate-500">
                        {{ item.section }}
                    </div>
                    <Link v-else :href="route(item.route)"
                        class="nav-link flex items-center px-3 py-2 rounded-lg"
                        :class="{ 'is-active': route().current(item.route) }">
                        {{ item.icon }} {{ item.label }}
                    </Link>
                </template>
            </nav>
            <div class="p-4 border-t border-slate-700 text-xs text-slate-500">
                {{ page.props.siteName || '企业官网' }} 后台<br>
                © {{ new Date().getFullYear() }} 版权所有
            </div>
        </aside>

        <!-- 移动端遮罩 -->
        <transition
            enter-active-class="transition-opacity duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="sidebarOpen" @click="closeSidebar"
                class="fixed inset-0 bg-black/40 z-30 lg:hidden"></div>
        </transition>

        <!-- 主内容 -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-full">
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button type="button" @click.stop="toggleSidebar" aria-label="切换菜单"
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 5A.75.75 0 012.75 9h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 9.75zm0 5a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <slot name="header">
                        <h1 class="text-lg font-semibold text-gray-800">{{ page.props.pageTitle || '后台管理' }}</h1>
                    </slot>
                </div>
                <div class="flex items-center gap-4">
                    <Link v-if="route().current('admin.dashboard')" href="#"
                        class="hidden md:inline-flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> 在线
                    </Link>
                    <a :href="'/'" target="_blank" class="text-sm text-blue-600 hover:underline">查看网站 →</a>
                    <div class="relative" ref="menuRoot">
                        <button type="button" @click.stop="toggleMenu"
                            class="flex items-center gap-2 group focus:outline-none">
                            <span class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium text-sm flex-shrink-0 shadow-sm">
                                {{ firstName }}
                            </span>
                            <span class="text-sm text-gray-700 hidden sm:inline max-w-[120px] truncate">{{ user.name }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :style="{ transform: userMenuOpen ? 'rotate(180deg)' : 'rotate(0deg)' }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <transition
                            enter-active-class="transition-all duration-150 ease-out"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition-all duration-150 ease-in"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95">
                            <div v-if="userMenuOpen"
                                class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 origin-top-right">
                                <div class="px-4 py-2.5 border-b border-gray-100 flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium flex-shrink-0">
                                        {{ firstName }}
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium text-gray-800 truncate">{{ user.name }}</div>
                                        <div class="text-xs text-gray-400 truncate">{{ user.email }}</div>
                                    </div>
                                </div>
                                <Link :href="route('admin.profile.password')" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <span>🔑</span> 修改密码
                                </Link>
                                <a :href="'/'" target="_blank" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <span>🌐</span> 查看网站
                                </a>
                                <Link :href="route('admin.logout')" method="post" as="button"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition text-left">
                                    <span>🚪</span> 退出登录
                                </Link>
                            </div>
                        </transition>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
.nav-link { position: relative; transition: background-color .18s ease, color .18s ease; color: #cbd5e1; }
.nav-link:hover { background-color: rgb(51 65 85); color: #fff; }
.nav-link.is-active { background-color: rgb(51 65 85); color: #fff; }
</style>
