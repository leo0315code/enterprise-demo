@forelse($sections as $section)
<tr class="section-row transition-colors duration-300 hover:bg-slate-50" data-id="{{ $section->id }}">
    <td class="px-6 py-3 text-gray-400">{{ $section->sort }}</td>
    <td class="px-6 py-3"><span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs">{{ $section->type }}</span></td>
    <td class="px-6 py-3 font-medium text-gray-800">{{ $section->title ?: '-' }}</td>
    <td class="px-6 py-3">
        @if($section->is_active)
            <span class="text-green-600">● 显示</span>
        @else
            <span class="text-gray-400">○ 隐藏</span>
        @endif
    </td>
    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
        <button type="button" class="text-blue-600 hover:underline" onclick="openSectionModal({{ $section->id }})">编辑</button>
        <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="inline" onsubmit="return confirm('确认删除该板块？');">
            @csrf @method('DELETE')
            <button class="text-red-500 hover:underline">删除</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">暂无板块</td></tr>
@endforelse
