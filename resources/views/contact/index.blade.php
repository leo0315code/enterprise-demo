@extends('layouts.app')

@section('title', '联系我们')

@section('content')
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">联系我们</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                @if(setting('contact_phone'))
                    <p class="mb-3"><span class="font-medium">电话：</span>{{ setting('contact_phone') }}</p>
                @endif
                @if(setting('contact_email'))
                    <p class="mb-3"><span class="font-medium">邮箱：</span>{{ setting('contact_email') }}</p>
                @endif
                @if(setting('contact_address'))
                    <p class="mb-3"><span class="font-medium">地址：</span>{{ setting('contact_address') }}</p>
                @endif
            </div>
            <div>
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="您的姓名" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <input type="email" name="email" placeholder="您的邮箱" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none">
                    <textarea name="message" rows="4" placeholder="您的留言" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary outline-none"></textarea>
                    <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg font-medium hover:opacity-90 transition">提交留言</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
