<!DOCTYPE html>
<html lang="zh-CN">
<body style="font-family: -apple-system, 'PingFang SC', 'Microsoft YaHei', sans-serif; color: #333; line-height: 1.8;">
    <h2 style="margin-bottom: 16px;">收到一条新的官网留言</h2>
    <table cellpadding="8" cellspacing="0" border="0" style="border-collapse: collapse;">
        <tr>
            <td style="color: #888;">姓名</td>
            <td>{{ $msg->name }}</td>
        </tr>
        <tr>
            <td style="color: #888;">邮箱</td>
            <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
        </tr>
        @if($msg->phone)
        <tr>
            <td style="color: #888;">电话</td>
            <td>{{ $msg->phone }}</td>
        </tr>
        @endif
        @if($msg->subject)
        <tr>
            <td style="color: #888;">主题</td>
            <td>{{ $msg->subject }}</td>
        </tr>
        @endif
        <tr>
            <td style="color: #888;">时间</td>
            <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
        </tr>
    </table>
    <p style="margin-top: 16px; padding: 12px 16px; background: #f6f7f9; border-radius: 8px; white-space: pre-wrap;">{{ $msg->message }}</p>
</body>
</html>
