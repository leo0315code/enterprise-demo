@forelse($posts as $post)
<tr class="transition-colors duration-300 hover:bg-slate-50">
    <td class="px-6 py-3">
        @if($post->cover)
            <img src="{{ $post->cover }}" alt="{{ $post->title }}" class="w-12 h-12 object-cover rounded" onerror="this.outerHTML='<div class=\'w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded flex items-center justify-center text-sm text-slate-400\'>📰</div>'">
        @else
            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded flex items-center justify-center text-sm text-slate-400">📰</div>
        @endif
    </td>
    <td class="px-6 py-3 font-medium text-gray-800">
        {{ $post->title }}
        @if($post->is_featured)<span class="text-xs text-amber-600 ml-1">★头条</span>@endif
    </td>
    <td class="px-6 py-3 text-gray-500">{{ $post->category->name ?? '未分类' }}</td>
    <td class="px-6 py-3 text-gray-400">{{ $post->published_at?->format('Y-m-d') ?? '-' }}</td>
    <td class="px-6 py-3">
        @if($post->is_active)<span class="inline-flex items-center text-green-600"><span class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>公开</span>@else<span class="inline-flex items-center text-gray-400"><span class="w-2 h-2 rounded-full bg-gray-300 mr-1.5"></span>草稿</span>@endif
    </td>
    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
        <button type="button" class="text-blue-600 hover:underline" onclick="CrudModal.open('{{ $post->getRouteKey() }}')">编辑</button>
        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('确认删除？');">
            @csrf @method('DELETE')
            <button class="text-red-500 hover:underline">删除</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">暂无文章，<a href="javascript:void(0)" onclick="CrudModal.open(null)" class="text-blue-600">去发布</a></td></tr>
@endforelse
