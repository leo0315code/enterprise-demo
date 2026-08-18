# Docker 配置

使用 Docker 快速启动开发环境。

## 快速开始

```bash
# 给启动脚本添加执行权限
chmod +x start-docker.sh

# 启动 Docker 环境
./start-docker.sh
```

## 手动启动

```bash
# 构建并启动服务
docker-compose -f docker-compose.dev.yml up -d --build

# 等待服务启动后，进入容器执行数据库迁移
sleep 20
docker-compose -f docker-compose.dev.yml exec app php artisan migrate:fresh --seed
```

## 访问应用

- **前台**: http://localhost:8000
- **后台**: http://localhost:8000/manage (默认账号: admin@example.com / admin123)

## 停止服务

```bash
docker-compose -f docker-compose.dev.yml down
```