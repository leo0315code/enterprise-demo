<!DOCTYPE html>
<html lang="zh-CN" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '后台管理') - {{ setting('site_name', config('app.name')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 text-white' : '' }}">
                    📊 仪表盘
                </a>
                <div class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-slate-500">内容配置</div>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-700 text-white' : '' }}">
                    ⚙️ 站点设置
                </a>
                <a href="{{ route('admin.sections.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.sections.*') ? 'bg-slate-700 text-white' : '' }}">
                    🏠 首页板块
                </a>
                <a href="{{ route('admin.pages.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.pages.*') ? 'bg-slate-700 text-white' : '' }}">
                    📄 单页管理
                </a>
                <div class="px-3 pt-4 pb-1 text-xs uppercase tracking-wider text-slate-500">内容管理</div>
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.products.*') ? 'bg-slate-700 text-white' : '' }}">
                    📦 产品服务
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.categories.*') ? 'bg-slate-700 text-white' : '' }}">
                    🏷️ 产品分类
                </a>
                <a href="{{ route('admin.posts.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.posts.*') ? 'bg-slate-700 text-white' : '' }}">
                    📰 新闻文章
                </a>
                <a href="{{ route('admin.post-categories.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.post-categories.*') ? 'bg-slate-700 text-white' : '' }}">
                    🗂️ 文章分类
                </a>
                <a href="{{ route('admin.messages.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-slate-700 {{ request()->routeIs('admin.messages.*') ? 'bg-slate-700 text-white' : '' }}">
                    ✉️ 留言管理
                </a>
            </nav>
            <div class="p-4 border-t border-slate-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium text-sm flex-shrink-0">
                        {{ mb_substr(auth()->user()->name ?? '管', 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm text-white font-medium truncate">{{ auth()->user()->name ?? '管理员' }}</div>
                        <div class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center text-xs text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg py-2 transition">退出登录</button>
                </form>
            </div>
        </aside>

        <!-- 主内容 -->
        <div class="flex-1 ml-64 flex flex-col min-h-full">
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 sticky top-0 z-10">
                <h1 class="text-lg font-semibold text-gray-800">@yield('page_title', '后台管理')</h1>
                <a href="{{ url('/') }}" target="_blank" class="text-sm text-blue-600 hover:underline">查看网站 →</a>
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
</body>
</html>
