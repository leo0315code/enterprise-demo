<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台登录 - {{ setting('site_name', config('app.name')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-800 to-slate-900 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 rounded-2xl bg-blue-500 shadow-lg flex items-center justify-center text-white text-2xl font-bold mb-4"
                 id="login-avatar">{{ mb_substr(setting('site_name', config('app.name')), 0, 1) }}</div>
            <div class="text-white text-2xl font-bold">{{ setting('site_name', config('app.name')) }}</div>
            <div class="text-slate-400 text-sm mt-1">企业官网后台管理系统</div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">用户名 / 邮箱</label>
                    <input type="text" name="login" value="{{ old('login') }}" required autofocus
                        placeholder="请输入用户名或邮箱"
                        class="w-full rounded-lg border-gray-300 border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">密码</label>
                    <input type="password" name="password" required
                        class="w-full rounded-lg border-gray-300 border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded"> 记住我
                </label>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium">
                    登录
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="text-slate-400 text-sm hover:text-white">← 返回网站首页</a>
        </div>
    </div>

    <script>
    (function () {
        const input = document.querySelector('input[name="login"]');
        const avatar = document.getElementById('login-avatar');
        const fallback = '{{ mb_substr(setting('site_name', config('app.name')), 0, 1) }}';
        if (!input || !avatar) return;
        input.addEventListener('input', () => {
            const v = input.value.trim();
            avatar.textContent = v ? Array.from(v)[0] : fallback;
        });
    })();
    </script>
</body>
</html>
