<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                @if(setting('site_logo'))
                    <img src="{{ setting('site_logo') }}" alt="{{ setting('site_name') }}" class="h-9">
                @else
                    <span class="text-xl font-bold text-primary">{{ setting('site_name', '企业官网') }}</span>
                @endif
            </a>

            <!-- 导航 -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary font-medium transition">首页</a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-primary font-medium transition">关于我们</a>
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-primary font-medium transition">产品服务</a>
                <a href="{{ route('posts.index') }}" class="text-gray-700 hover:text-primary font-medium transition">新闻动态</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary font-medium transition">联系我们</a>
            </nav>

            <a href="{{ route('contact') }}" class="hidden md:inline-flex bg-primary hover:opacity-90 text-white px-5 py-2 rounded-lg font-medium transition">
                立即咨询
            </a>

            <!-- 移动端菜单按钮 -->
            <button class="md:hidden p-2" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <!-- 移动端菜单 -->
        <div id="mobile-menu" class="md:hidden hidden pb-4 border-t border-gray-100">
            <nav class="flex flex-col gap-3 pt-4">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary">首页</a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-primary">关于我们</a>
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-primary">产品服务</a>
                <a href="{{ route('posts.index') }}" class="text-gray-700 hover:text-primary">新闻动态</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-primary">联系我们</a>
            </nav>
        </div>
    </div>
</header>
