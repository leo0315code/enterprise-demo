# 通用企业官网 (Laravel 13)

一套可灵活配置的通用企业官网系统，基于 Laravel 13 + MySQL + Tailwind CSS v4 + Vite 构建。

## 核心特性

- **站点配置可视化**：Logo、联系方式、SEO、主题色等通过后台统一配置，无需改代码
- **首页可配置**：Hero Banner、公司简介、核心优势、产品/新闻板块、CTA 等自由组合排序
- **内容管理**：产品/服务、新闻文章、单页（关于/联系）、产品与文章分类
- **留言系统**：联系表单提交入库（honeypot 防垃圾 + 限流），后台可查看/标记已读/回复，新留言邮件通知
- **后台管理**：独立后台（路径可配置，默认 `/manage`），账号密码登录（登录限流防暴力破解），侧边栏分组导航
- **SEO 友好**：服务端渲染、动态 sitemap.xml、OG/canonical meta、JSON-LD 结构化数据、面包屑导航
- **前台体验**：站内搜索、文章上一篇/下一篇与相关推荐、产品相关推荐、浏览量统计、自定义错误页
- **图片优化**：上传自动生成 600px 列表缩略图（旧数据可用 `php artisan media:generate-thumbs` 回填），列表页懒加载
- **安全加固**：上传扩展名白名单（基于 MIME 推断）、安全响应头、定时发布（文章未到发布时间不可见）
- **响应式设计**：移动端 / 桌面端自适应

## 环境要求

- PHP >= 8.3
- MySQL 5.7+ (本项目使用 ServBay MySQL 8.0)
- Node.js 20+ / npm

## 安装

```bash
# 1. 安装 PHP 依赖
composer install

# 2. 复制环境变量并生成密钥
cp .env.example .env
php artisan key:generate

# 3. 配置 .env 数据库连接 (已默认指向本地 enterprise_website 库)
#   DB_CONNECTION=mysql
#   DB_HOST=127.0.0.1
#   DB_PORT=3306
#   DB_DATABASE=enterprise_website
#   DB_USERNAME=root
#   DB_PASSWORD=root

# 4. 创建数据库并执行迁移 + 填充
php artisan migrate:fresh --seed

# 5. 安装前端依赖并构建
npm install
npm run build

# 6. 启动开发服务器
php artisan serve
```

访问 `http://localhost:8000`，后台 `http://localhost:8000/manage`

> 后台入口路径由 `.env` 的 `ADMIN_PREFIX` 控制（默认 `manage`，出于安全考虑已不再是常见的 `admin`），修改后请执行 `php artisan route:clear`。

## 默认管理员

- 邮箱：`admin@example.com`
- 密码：`admin123`（**弱口令，首次登录后请尽快修改**，仪表盘会给出安全提醒）

> 生产环境务必修改管理员密码：可用 `php artisan tinker` 执行 `App\Models\User::first()->update(['password'=>bcrypt('新密码')])`，或重新 seed 前在 `DatabaseSeeder` 中调整。

## 目录结构

```
app/
  Models/            数据模型 (SiteSetting, HomepageSection, Page, Product,
                     Category, Post, ContactMessage, User)
  Http/Controllers/
    Admin/           后台管理控制器
    HomeController/PageController/...  前台控制器
database/
  migrations/        数据库迁移
  seeders/           初始数据填充
resources/
  views/
    layouts/         前台公共布局（含 SEO meta 插槽）
    components/      头部 / 页脚组件
    home.blade.php   首页 (按板块类型渲染)
    errors/          自定义 404/429/500 错误页
  js/                后台 Inertia/Vue3 页面与组件
routes/
  web.php            前台路由
  admin.php          后台路由
```

## 自定义首页板块类型

`homepage_sections` 表的 `type` 字段支持：
`hero`(Banner) | `intro`(简介) | `features`(卡片) |
`products`(推荐产品) | `news`(最新新闻) | `cta`(行动召唤) | `custom`(自定义)

`features` 类型的 `extra` 字段为 JSON 数组：`[{"icon":"🚀","title":"标题","desc":"描述"}]`
