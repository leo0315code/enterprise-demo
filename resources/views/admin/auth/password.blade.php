@extends('layouts.admin')

@section('page_title', '修改密码')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="font-semibold text-gray-800 mb-1">修改登录密码</h2>
        <p class="text-sm text-gray-500 mb-6">建议设置 8 位以上、包含字母与数字的强密码，定期更换以保障后台安全。</p>

        <form action="{{ route('admin.profile.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">当前密码</label>
                <input type="password" name="current_password" required autofocus
                    class="w-full rounded-lg border-gray-300 border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('current_password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">新密码</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border-gray-300 border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">确认新密码</label>
                <input type="password" name="password_confirmation" required
                    class="w-full rounded-lg border-gray-300 border px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm">
                    确认修改
                </button>
                <a href="{{ route('admin.dashboard') }}"
                    class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5">取消</a>
            </div>
        </form>
    </div>
</div>
@endsection
