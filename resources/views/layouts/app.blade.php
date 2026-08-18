<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('site_name', config('app.name'))) - {{ setting('site_slogan', '') }}</title>
    <meta name="description" content="@yield('description', setting('seo_description', ''))">
    <meta name="keywords" content="@yield('keywords', setting('seo_keywords', ''))">
    @hasSection('canonical')<link rel="canonical" href="@yield('canonical')">@endif
    {{-- Open Graph（社交分享） --}}
    <meta property="og:site_name" content="{{ setting('site_name', config('app.name')) }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', setting('site_name', config('app.name')))">
    <meta property="og:description" content="@yield('description', setting('seo_description', ''))">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    @hasSection('og_image')<meta property="og:image" content="@yield('og_image')">@endif
    @stack('jsonld')
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
