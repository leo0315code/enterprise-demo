# 贡献指南

感谢您有兴趣为通用企业官网项目做出贡献！本文档提供了有关如何参与项目开发的详细指导。

## 开发环境设置

### 系统要求
- PHP 8.3 或更高版本
- Composer
- Node.js 20 或更高版本
- MySQL 5.7 或更高版本
- Git

### 本地开发设置
1. Fork 仓库
2. 克隆代码：
   ```bash
   git clone https://github.com/YOUR_USERNAME/enterprise-website.git
   cd enterprise-website
   ```
3. 安装 PHP 依赖：
   ```bash
   composer install
   ```
4. 安装前端依赖：
   ```bash
   npm install
   ```
5. 复制环境配置：
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
6. 配置数据库连接并运行迁移：
   ```bash
   php artisan migrate --seed
   ```
7. 构建前端资源：
   ```bash
   npm run dev  # 开发模式
   # 或
   npm run build  # 生产模式
   ```

## 代码规范

### PHP 规范
- 遵循 PSR-12 编码标准
- 使用有意义的变量和函数命名
- 保持函数简短，专注于单一职责
- 添加适当的注释和文档块

### JavaScript/TypeScript 规范
- 遵循 ESLint 和 Prettier 配置
- 使用有意义的变量名
- 保持组件简洁，遵循单一职责原则

### CSS 规范
- 使用 Tailwind CSS 实用优先的方法
- 避免自定义 CSS，除非绝对必要
- 保持样式一致性和可维护性

## 分支管理

- 主要开发分支：`main`
- 功能开发：`feature/功能名称`
- Bug 修复：`fix/问题描述`
- 发布版本：`release/版本号`

### 分支命名约定
```
feature/user-authentication
fix/login-validation-error
hotfix/security-patch
```

## 提交信息规范

使用约定式提交格式：
```
<type>(<scope>): <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```

### 提交类型
- `feat`: 新功能
- `fix`: Bug 修复
- `docs`: 文档更新
- `style`: 代码格式调整（不影响逻辑）
- `refactor`: 代码重构
- `test`: 测试相关
- `chore`: 构建过程或辅助工具变动

### 示例
```
feat(auth): 添加用户登录限流功能

- 实现登录尝试限制
- 添加验证码选项
- 更新相关测试

Closes #123
```

## 测试

### 运行测试
```bash
# 运行所有测试
php artisan test

# 运行单元测试
php artisan test --testsuite=Unit

# 运行功能测试
php artisan test --testsuite=Feature
```

### 测试覆盖率
- 目标覆盖率达到 80% 以上
- 新功能必须包含相应测试
- Bug 修复应包含回归测试

## Pull Request 流程

1. 确保分支是最新的：
   ```bash
   git checkout main
   git pull origin main
   git checkout your-branch
   git rebase main
   ```
2. 运行测试确保一切正常：
   ```bash
   php artisan test
   ```
3. 提交更改：
   ```bash
   git add .
   git commit -m "feat: 添加新功能"
   git push origin your-branch
   ```
4. 创建 Pull Request
5. 等待代码审查
6. 根据反馈进行修改
7. 合并（由维护者完成）

## 代码审查清单

### 功能方面
- [ ] 功能按预期工作
- [ ] 没有破坏现有功能
- [ ] 符合项目需求

### 代码质量
- [ ] 遵循编码规范
- [ ] 代码可读性强
- [ ] 适当使用注释
- [ ] 没有明显的性能问题

### 测试
- [ ] 包含适当的单元测试
- [ ] 测试覆盖了边界情况
- [ ] 所有测试都通过

### 安全
- [ ] 没有安全漏洞
- [ ] 输入已验证和清理
- [ ] 敏感信息得到保护

## Issue 报告

### Bug 报告
当报告 Bug 时，请包含：
1. 版本信息
2. 环境配置
3. 重现步骤
4. 预期行为
5. 实际行为
6. 相关截图或日志

### 功能请求
请描述：
1. 功能描述
2. 使用场景
3. 预期收益

## 技术架构

### 后端技术栈
- Laravel 13
- MySQL
- Redis（可选，用于缓存和队列）

### 前端技术栈
- Vue 3
- Inertia.js
- Tailwind CSS
- Vite

### 目录结构
```
app/                # Laravel 应用代码
  Console/          # Artisan 命令
  Exceptions/       # 异常处理
  Http/             # 控制器、中间件、请求验证
  Models/           # Eloquent 模型
  Providers/        # 服务提供者
bootstrap/          # 框架启动文件
config/             # 配置文件
database/           # 迁移、工厂、种子
public/             # 公开访问的文件
resources/          # 视图、CSS、JS 资源
  js/               # 前端 JavaScript
    Components/     # Vue 组件
    Pages/          # 页面组件
  views/            # Blade 模板
routes/             # 路由定义
storage/            # 存储文件
tests/              # 测试文件
```

## 开发最佳实践

### 数据库
- 使用迁移管理数据库结构
- 使用模型工厂进行测试数据创建
- 使用 Eloquent ORM 进行数据库操作

### 安全
- 验证所有用户输入
- 使用 Laravel 的 CSRF 保护
- 使用预处理语句防止 SQL 注入
- 对输出进行适当转义

### 性能
- 使用缓存提高性能
- 优化数据库查询
- 使用队列处理耗时任务
- 压缩前端资源

## 联系方式

如有疑问，请通过以下方式联系：
- GitHub Issues
- 项目邮件列表