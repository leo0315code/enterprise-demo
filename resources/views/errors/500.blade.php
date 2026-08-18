{{-- 500 错误页：刻意不继承站点布局、不读取任何配置，避免数据库/缓存故障时二次报错 --}}
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>服务器错误</title>
    <style>
        body { font-family: -apple-system, "PingFang SC", "Microsoft YaHei", sans-serif; background: #f6f7f9; color: #1f2937; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .box { text-align: center; padding: 24px; }
        .code { font-size: 72px; font-weight: 700; color: #2563eb; }
        p { color: #6b7280; }
        a { display: inline-block; margin-top: 16px; background: #2563eb; color: #fff; padding: 10px 24px; border-radius: 8px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <div class="code">500</div>
        <h1>服务器开小差了</h1>
        <p>页面暂时无法访问，我们正在尽快修复，请稍后再试。</p>
        <a href="/">返回首页</a>
    </div>
</body>
</html>
