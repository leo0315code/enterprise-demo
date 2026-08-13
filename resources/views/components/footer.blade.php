<footer class="bg-dark text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- 公司信息 -->
            <div>
                <div class="text-white text-lg font-bold mb-4">{{ setting('site_name', '企业官网') }}</div>
                <p class="text-sm text-gray-400 leading-relaxed">{{ setting('site_description', '') }}</p>
            </div>

            <!-- 快速链接 -->
            <div>
                <div class="text-white font-semibold mb-4">快速链接</div>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-primary transition">首页</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-primary transition">关于我们</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-primary transition">产品服务</a></li>
                    <li><a href="{{ route('posts.index') }}" class="hover:text-primary transition">新闻动态</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary transition">联系我们</a></li>
                </ul>
            </div>

            <!-- 联系方式 -->
            <div>
                <div class="text-white font-semibold mb-4">联系我们</div>
                <ul class="space-y-2 text-sm text-gray-400">
                    @if(setting('contact_phone'))
                        <li>📞 {{ setting('contact_phone') }}</li>
                    @endif
                    @if(setting('contact_email'))
                        <li>✉️ {{ setting('contact_email') }}</li>
                    @endif
                    @if(setting('contact_address'))
                        <li>📍 {{ setting('contact_address') }}</li>
                    @endif
                    @if(setting('work_time'))
                        <li>🕐 {{ setting('work_time') }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm text-gray-500">
            {{ setting('theme_footer_text', '© ' . date('Y') . ' ' . setting('site_name', '企业官网')) }}
            @if(setting('icp_number'))
                <span class="ml-2">{{ setting('icp_number') }}</span>
            @endif
        </div>
    </div>
</footer>
