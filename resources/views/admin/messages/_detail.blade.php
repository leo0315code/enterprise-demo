<div class="space-y-5 text-sm">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
        <div class="flex gap-3">
            <span class="text-gray-500 w-16 flex-shrink-0">姓名</span>
            <span class="font-medium text-gray-800">{{ $message->name }}</span>
        </div>
        <div class="flex gap-3">
            <span class="text-gray-500 w-16 flex-shrink-0">邮箱</span>
            <a href="mailto:{{ $message->email }}" class="text-blue-600 hover:underline break-all">{{ $message->email }}</a>
        </div>
        @if($message->phone)
        <div class="flex gap-3">
            <span class="text-gray-500 w-16 flex-shrink-0">电话</span>
            <span class="text-gray-800">{{ $message->phone }}</span>
        </div>
        @endif
        <div class="flex gap-3">
            <span class="text-gray-500 w-16 flex-shrink-0">时间</span>
            <span class="text-gray-500">{{ $message->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div class="flex gap-3">
            <span class="text-gray-500 w-16 flex-shrink-0">状态</span>
            @if($message->is_read)
                <span class="text-green-600">● 已读</span>
            @else
                <span class="text-blue-600 font-medium">● 未读</span>
            @endif
        </div>
        @if($message->replied_at)
        <div class="flex gap-3">
            <span class="text-gray-500 w-16 flex-shrink-0">回复于</span>
            <span class="text-gray-500">{{ $message->replied_at->format('Y-m-d H:i') }}</span>
        </div>
        @endif
    </div>

    <div>
        <div class="text-gray-500 mb-2">主题</div>
        <div class="text-gray-800 font-medium">{{ $message->subject ?: '（无主题）' }}</div>
    </div>

    <div>
        <div class="text-gray-500 mb-2">留言内容</div>
        <div class="p-4 bg-gray-50 rounded-lg text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $message->message }}</div>
    </div>

    <div class="flex justify-end gap-3 pt-1">
        <a href="mailto:{{ $message->email }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition">回复邮件</a>
    </div>
</div>
