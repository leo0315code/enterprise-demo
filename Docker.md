# Docker 配置说明

## 简介

本项目提供了 Docker 配置，用于快速搭建开发和测试环境。使用 Laravel 自带的 Artisan 服务器配合 MySQL 和 Redis 服务。

## 组件

- **PHP 8.3**: 运行 Laravel 应用
- **MySQL 8.0**: 数据库服务
- **Redis**: 缓存和会话存储
- **Laravel Artisan 服务器**: Laravel 内置开发服务器

## 系统要求

- Docker Engine 20.10+
- Docker Compose v2+

## 快速启动

### 方法一：使用启动脚本（推荐）

```bash
# 给启动脚本添加执行权限
chmod +x start-docker.sh

# 启动服务
./start-docker.sh
```

### 方法二：手动启动

```bash
# 构建并启动服务
docker-compose -f docker-compose.dev.yml up -d --build

# 等待服务启动后，进入容器执行数据库迁移
sleep 20
docker-compose -f docker-compose.dev.yml exec app php artisan migrate:fresh --seed
```

## 访问地址

- **前台网站**: http://localhost:8000
- **后台管理**: http://localhost:8000/manage
- **数据库**: localhost:3306 (用户名: user, 密码: password)

## 默认管理员账号

- **邮箱**: admin@example.com
- **密码**: admin123

## 常用命令

```bash
# 查看服务状态
docker-compose -f docker-compose.dev.yml ps

# 查看日志
docker-compose -f docker-compose.dev.yml logs -f

# 进入应用容器
docker-compose -f docker-compose.dev.yml exec app bash

# 运行测试
docker-compose -f docker-compose.dev.yml exec app php artisan test

# 停止服务
docker-compose -f docker-compose.dev.yml down

# 重启服务
docker-compose -f docker-compose.dev.yml restart

# 查看容器资源使用情况
docker stats
```

## 配置说明

### 环境变量

启动脚本会自动更新 `.env` 文件中的以下配置：

- `DB_HOST=mysql` - 数据库主机
- `DB_PORT=3306` - 数据库端口
- `DB_DATABASE=enterprise_website` - 数据库名
- `DB_USERNAME=user` - 数据库用户名
- `DB_PASSWORD=password` - 数据库密码
- `REDIS_HOST=redis` - Redis 主机
- `CACHE_STORE=redis` - 缓存驱动
- `SESSION_DRIVER=redis` - 会话驱动
- `QUEUE_CONNECTION=redis` - 队列连接

### 数据持久化

- MySQL 数据存储在 `mysql_data` 卷中
- Redis 数据存储在 `redis_data` 卷中

## 故障排除

### 服务无法启动

1. 检查端口是否被占用
2. 检查 Docker 是否正常运行
3. 查看详细日志：`docker-compose -f docker-compose.dev.yml logs`

### 数据库连接失败

1. 确认 `.env` 文件配置正确
2. 检查 MySQL 服务是否正常运行：`docker-compose -f docker-compose.dev.yml logs mysql`
3. 等待 MySQL 完全启动后再执行迁移

### 内存不足

如果遇到内存不足问题，可以修改 `mysql/my.cnf` 中的配置：

```ini
[mysqld]
innodb_buffer_pool_size = 128M  # 减少内存使用
```

## 生产环境说明

此 Docker 配置主要用于开发和测试环境。生产环境部署请参考 [DEPLOYMENT.md](DEPLOYMENT.md) 文档。

生产环境建议使用：
- Nginx 作为 Web 服务器
- PHP-FPM 作为 PHP 处理器
- 更高的资源配置
- 适当的安全配置