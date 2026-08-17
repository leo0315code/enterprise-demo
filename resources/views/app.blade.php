<!DOCTYPE html>
<html lang="zh-CN" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '后台管理') - {{ setting('site_name', config('app.name')) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="upload-url" content="{{ route('admin.upload.image') }}">
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-100">
    @inertia
</body>
</html>
