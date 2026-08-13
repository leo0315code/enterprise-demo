@forelse($categories as $category)
<tr class="transition-colors duration-300 hover:bg-slate-50">
    <td class="px-6 py-3 font-medium text-gray-800">{{ $category->name }}</td>
    <td class="px-6 py-3 text-gray-400">/{{ $category->slug }}</td>
    <td class="px-6 py-3">
        @if($category->is_active)<span class="text-green-600">● 启用</span>@else<span class="text-gray-400">○ 停用</span>@endif
    </td>
    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
        <button type="button" class="text-blue-600 hover:underline" onclick="CrudModal.open('{{ $category->slug }}')">编辑</button>
        <form action="{{ route('admin.post-categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('删除该分类？');">
            @csrf @method('DELETE')
            <button class="text-red-500 hover:underline">删除</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">暂无分类</td></tr>
@endforelse
