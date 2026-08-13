@forelse($pages as $page)
<tr class="transition-colors duration-300 hover:bg-slate-50">
    <td class="px-6 py-3 font-medium text-gray-800">{{ $page->title }}</td>
    <td class="px-6 py-3 text-gray-400">/{{ $page->slug }}</td>
    <td class="px-6 py-3">
        @if($page->is_active)<span class="text-green-600">● 显示</span>@else<span class="text-gray-400">○ 隐藏</span>@endif
    </td>
    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
        <button type="button" class="text-blue-600 hover:underline" onclick="CrudModal.open('{{ $page->getRouteKey() }}')">编辑</button>
        @unless(in_array($page->slug, ['about','contact']))
        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('确认删除？');">
            @csrf @method('DELETE')
            <button class="text-red-500 hover:underline">删除</button>
        </form>
        @endunless
    </td>
</tr>
@empty
<tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">暂无页面</td></tr>
@endforelse
