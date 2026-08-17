@forelse($messages as $message)
<tr class="transition-colors duration-300 hover:bg-slate-50 {{ $message->is_read ? '' : 'bg-blue-50/40' }}">
    <td class="px-6 py-3">
        @if($message->is_read)<span class="text-gray-400">○ 已读</span>@else<span class="text-blue-600 font-medium">● 未读</span>@endif
    </td>
    <td class="px-6 py-3 font-medium text-gray-800">{{ $message->name }}</td>
    <td class="px-6 py-3 text-gray-500">{{ $message->email }}</td>
    <td class="px-6 py-3 text-gray-600">{{ $message->subject ?: '（无主题）' }}</td>
    <td class="px-6 py-3 text-gray-400">{{ $message->created_at->format('Y-m-d H:i') }}</td>
    <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
        <button type="button" class="text-blue-600 hover:underline" onclick="MessageModal.view('{{ $message->getRouteKey() }}')">查看</button>
        <button type="button" class="text-red-500 hover:underline"
            onclick="MessageModal.remove('{{ $message->getRouteKey() }}')">删除</button>
    </td>
</tr>
@empty
<tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">暂无留言</td></tr>
@endforelse
