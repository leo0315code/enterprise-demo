#!/bin/bash

# Docker 环境启动脚本

echo "==========================================="
echo "企业官网 Docker 开发环境启动脚本"
echo "==========================================="

# 检查 Docker 是否已安装
if ! [ -x "$(command -v docker)" ]; then
  echo "错误: Docker 未安装，请先安装 Docker"
  exit 1
fi

# 检查 Docker Compose 是否已安装
if ! [ -x "$(command -v docker-compose)" ]; then
  echo "警告: Docker Compose 未安装，尝试使用 docker compose (Docker Desktop 集成版本)"
  if ! docker compose version > /dev/null 2>&1; then
    echo "错误: Docker Compose 未安装，请先安装 Docker Compose 或 Docker Desktop"
    exit 1
  fi
fi

# 检查 .env 文件是否存在
if [ ! -f ".env" ]; then
  echo ".env 文件不存在，从 .env.example 复制..."
  cp .env.example .env
  echo "已复制 .env 文件，请根据需要修改配置"
fi

# 更新 .env 文件中的数据库配置
echo "更新 .env 文件中的数据库配置..."
sed -i.bak 's/DB_HOST=.*/DB_HOST=mysql/' .env
sed -i.bak 's/DB_PORT=.*/DB_PORT=3306/' .env
sed -i.bak 's/DB_DATABASE=.*/DB_DATABASE=enterprise_website/' .env
sed -i.bak 's/DB_USERNAME=.*/DB_USERNAME=user/' .env
sed -i.bak 's/DB_PASSWORD=.*/DB_PASSWORD=password/' .env

# 更新缓存和会话配置
sed -i.bak 's/REDIS_HOST=.*/REDIS_HOST=redis/' .env
sed -i.bak 's/CACHE_STORE=.*/CACHE_STORE=redis/' .env
sed -i.bak 's/SESSION_DRIVER=.*/SESSION_DRIVER=redis/' .env
sed -i.bak 's/QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/' .env

# 构建并启动 Docker 服务
echo "正在构建并启动 Docker 服务..."
docker-compose -f docker-compose.dev.yml up -d --build

echo "等待服务启动..."
sleep 15

# 检查服务状态
echo "检查服务状态..."
docker-compose -f docker-compose.dev.yml ps

echo "==========================================="
echo "Docker 环境已启动！"
echo ""
echo "访问地址:"
echo "  - 前台: http://localhost:8000"
echo "  - 后台: http://localhost:8000/manage"
echo "  - 数据库: localhost:3306 (用户名: user, 密码: password)"
echo ""
echo "默认管理员账号:"
echo "  - 邮箱: admin@example.com"
echo "  - 密码: admin123"
echo ""
echo "常用命令:"
echo "  - 查看服务状态: docker-compose -f docker-compose.dev.yml ps"
echo "  - 查看日志: docker-compose -f docker-compose.dev.yml logs -f"
echo "  - 进入应用容器: docker-compose -f docker-compose.dev.yml exec app bash"
echo "  - 停止服务: docker-compose -f docker-compose.dev.yml down"
echo "  - 重启服务: docker-compose -f docker-compose.dev.yml restart"
echo "==========================================="