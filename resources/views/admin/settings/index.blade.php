@extends('layouts.admin')

@section('page_title', '站点设置')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
    @csrf
    @method('PUT')

    @foreach($groups as $group => $items)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-semibold text-gray-800">{{ $groupLabels[$group] ?? $group }}</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($items as $item)
            <div class="md:col-span-{{ $item->type === 'textarea' ? '2' : '1' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $item->label }}
                    @if($item->description)
                        <span class="text-xs font-normal text-gray-400 ml-1">{{ $item->description }}</span>
                    @endif
                </label>

                @if($item->type === 'textarea')
                    <textarea name="settings[{{ $item->key }}]" rows="3"
                        class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('settings.'.$item->key, $item->value) }}</textarea>
                @elseif($item->type === 'switch')
                    <select name="settings[{{ $item->key }}]"
                        class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="1" {{ $item->value ? 'selected' : '' }}>开启</option>
                        <option value="0" {{ !$item->value ? 'selected' : '' }}>关闭</option>
                    </select>
                @else
                    <input type="{{ $item->type === 'image' ? 'text' : 'text' }}" name="settings[{{ $item->key }}]"
                        value="{{ old('settings.'.$item->key, $item->value) }}"
                        placeholder="{{ $item->description }}"
                        class="w-full rounded-lg border-gray-300 border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @if($item->type === 'image' && $item->value)
                        <img src="{{ $item->value }}" alt="" class="mt-2 h-16 rounded border border-gray-200">
                    @endif
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="flex justify-end">
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm">
            保存设置
        </button>
    </div>
</form>
@endsection
