<!DOCTYPE html>
<html lang="zh-CN" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '后台管理') - {{ setting('site_name', config('app.name')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* 侧边栏导航：相对定位承载左侧高亮条 */
        .nav-link { position: relative; transition: background-color .18s ease, color .18s ease; }
        .nav-link::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%) scaleY(0);
            width: 3px; height: 60%; border-radius: 0 3px 3px 0; background: #3b82f6;
            transition: transform .18s ease;
        }
        .nav-link:hover { background-color: rgb(51 65 85); color: #fff; }
        .nav-link.is-active { background-color: rgb(51 65 85); color: #fff; }
        .nav-link.is-active::before { transform: translateY(-50%) scaleY(1); }
    </style>
</head>
<body class="h-full bg-gray-100">
    @php($adminPrefix = config('app.admin_prefix', 'manage'))
    <div class="min-h-full flex">
        <!-- 侧边栏 -->
        <aside class="w-64 bg-slate-800 text-gray-300 flex flex-col fixed h-full">
            <div class="h-16 flex items-center px-6 text-white font-bold text-lg border-b border-slate-700">
                {{ setting('site_name', config('app.name')) }} <span class="text-xs ml-2 text-slate-400 font-normal">后台</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    📊 仪表盘
                </a>
                <div class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-slate-500">内容配置</div>
                <a href="{{ route('admin.settings.index') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">
                    ⚙️ 站点设置
                </a>
                <a href="{{ route('admin.sections.index') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.sections.*') ? 'is-active' : '' }}">
                    🏠 首页板块
                </a>
                <a href="{{ route('admin.pages.index') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.pages.*') ? 'is-active' : '' }}">
                    📄 单页管理
                </a>
                <div class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-slate-500">内容管理</div>
                <a href="{{ route('admin.products.index') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">
                    📦 产品服务
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
                    🏷️ 产品分类
                </a>
                <a href="{{ route('admin.posts.index') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.posts.*') ? 'is-active' : '' }}">
                    📰 新闻文章
                </a>
                <a href="{{ route('admin.post-categories.index') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.post-categories.*') ? 'is-active' : '' }}">
                    🗂️ 文章分类
                </a>
                <a href="{{ route('admin.messages.index') }}" class="nav-link flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('admin.messages.*') ? 'is-active' : '' }}">
                    ✉️ 留言管理
                </a>
            </nav>
            <div class="p-4 border-t border-slate-700 text-xs text-slate-500">
                {{ setting('site_name', config('app.name')) }} 后台<br>
                © {{ date('Y') }} 版权所有
            </div>
        </aside>

        <!-- 主内容 -->
        <div class="flex-1 ml-64 flex flex-col min-h-full">
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 sticky top-0 z-30">
                <h1 class="text-lg font-semibold text-gray-800">@yield('page_title', '后台管理')</h1>
                <div class="flex items-center gap-4">
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="hidden md:inline-flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> 在线
                        </span>
                    @endif
                    <a href="{{ url('/') }}" target="_blank" class="text-sm text-blue-600 hover:underline">查看网站 →</a>
                    <div class="relative" id="user-menu">
                        <button type="button" id="user-menu-btn"
                            class="flex items-center gap-2 group focus:outline-none">
                            <span class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium text-sm flex-shrink-0 shadow-sm">
                                {{ mb_substr(auth()->user()->name ?? '管', 0, 1) }}
                            </span>
                            <span class="text-sm text-gray-700 hidden sm:inline max-w-[120px] truncate">{{ auth()->user()->name ?? '管理员' }}</span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="user-menu-caret" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="user-menu-panel"
                            class="hidden absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 origin-top-right">
                            <div class="px-4 py-2.5 border-b border-gray-100 flex items-center gap-3">
                                <span class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium flex-shrink-0">
                                    {{ mb_substr(auth()->user()->name ?? '管', 0, 1) }}
                                </span>
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name ?? '管理员' }}</div>
                                    <div class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</div>
                                </div>
                            </div>
                            <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <span>🌐</span> 查看网站
                            </a>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition text-left">
                                    <span>🚪</span> 退出登录
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
            @stack('scripts')
        </div>
    </div>

    <script>
    (function () {
        const root = document.getElementById('user-menu');
        const btn = document.getElementById('user-menu-btn');
        const panel = document.getElementById('user-menu-panel');
        const caret = document.getElementById('user-menu-caret');
        if (!root || !btn || !panel) return;
        function toggle(open) {
            const isOpen = panel.classList.toggle('hidden', !open);
            caret.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
        }
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const willOpen = panel.classList.contains('hidden');
            toggle(willOpen);
        });
        document.addEventListener('click', (e) => {
            if (!root.contains(e.target)) toggle(false);
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') toggle(false);
        });
    })();
    </script>
</body>
</html>
