<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('site_name', config('app.name'))) - {{ setting('site_slogan', '') }}</title>
    <meta name="description" content="@yield('description', setting('seo_description', ''))">
    <meta name="keywords" content="@yield('keywords', setting('seo_keywords', ''))">
    @vite('resources/css/app.css')
    <style>
        :root {
            --primary: {{ setting('theme_primary_color', '#2563eb') }};
        }
        .bg-primary { background-color: var(--primary); }
        .text-primary { color: var(--primary); }
        .border-primary { border-color: var(--primary); }
        .hover-bg-primary:hover { background-color: var(--primary); }
    </style>
</head>
<body class="bg-gray-bg text-dark antialiased">
    @include('components.header')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @if(session('success'))
        <div id="toast" class="fixed top-4 right-4 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg z-50 transition-opacity">
            {{ session('success') }}
        </div>
        <script>setTimeout(() => { const t = document.getElementById('toast'); if (t) t.style.opacity = '0'; }, 3000);</script>
    @endif
</body>
</html>
