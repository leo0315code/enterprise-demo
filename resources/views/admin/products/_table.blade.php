@forelse($products as $product)
<tr class="transition-colors duration-300 hover:bg-slate-50">
    <td class="px-6 py-3">
        @if($product->thumbnail)
            <img src="{{ $product->thumbnail }}" class="w-12 h-12 object-cover rounded">
        @else
            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-xl">📦</div>
        @endif
    </td>
    <td class="px-6 py-3 font-medium text-gray-800">
        {{ $product->title }}
        @if($product->is_featured)<span class="text-xs text-amber-600 ml-1">★推荐</span>@endif
    </td>
    <td class="px-6 py-3 text-gray-500">{{ $product->category->name ?? '未分类' }}</td>
    <td class="px-6 py-3">
        @if($product->status == 'active')<span class="inline-flex items-center text-green-600"><span class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>上架</span>@else<span class="inline-flex items-center text-gray-400"><span class="w-2 h-2 rounded-full bg-gray-300 mr-1.5"></span>下架</span>@endif
    </td>
    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
        <button type="button" class="text-blue-600 hover:underline" onclick="CrudModal.open('{{ $product->getRouteKey() }}')">编辑</button>
        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('确认删除？');">
            @csrf @method('DELETE')
            <button class="text-red-500 hover:underline">删除</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">暂无产品，<a href="javascript:void(0)" onclick="CrudModal.open(null)" class="text-blue-600">去新增</a></td></tr>
@endforelse
