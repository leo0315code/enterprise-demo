@extends('layouts.app')

@section('title', '联系我们')

@section('content')
<section class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-3">联系我们</h1>
        <p class="text-blue-100">期待与您的每一次沟通</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- 左侧：联系方式 -->
        <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">联系方式</h2>
            <div class="space-y-5">
                @if(setting('contact_phone'))
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📞</span>
                        <div><div class="font-medium text-gray-900">联系电话</div><div class="text-gray-600">{{ setting('contact_phone') }}</div></div>
                    </div>
                @endif
                @if(setting('contact_email'))
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">✉️</span>
                        <div><div class="font-medium text-gray-900">电子邮箱</div><div class="text-gray-600">{{ setting('contact_email') }}</div></div>
                    </div>
                @endif
                @if(setting('contact_address'))
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">📍</span>
                        <div><div class="font-medium text-gray-900">公司地址</div><div class="text-gray-600">{{ setting('contact_address') }}</div></div>
                    </div>
                @endif
                @if(setting('work_time'))
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🕐</span>
                        <div><div class="font-medium text-gray-900">工作时间</div><div class="text-gray-600">{{ setting('work_time') }}</div></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 右侧：留言表单 -->
        <div class="bg-gray-bg rounded-2xl p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">在线留言</h2>
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="name" placeholder="您的姓名" required value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none @error('name') border-red-400 @enderror">
                    <input type="tel" name="phone" placeholder="联系电话" value="{{ old('phone') }}" class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                </div>
                <input type="email" name="email" placeholder="您的邮箱" required value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none @error('email') border-red-400 @enderror">
                <input type="text" name="subject" placeholder="主题（选填）" value="{{ old('subject') }}" class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                <textarea name="message" rows="4" placeholder="您的留言内容" required class="w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                @error('message')<p class="text-sm text-red-500">{{ $message }}</p>@enderror
                <button type="submit" class="w-full bg-primary text-white px-6 py-3 rounded-lg font-medium hover:opacity-90 transition">提交留言</button>
            </form>
        </div>
    </div>
</section>
@endsection
