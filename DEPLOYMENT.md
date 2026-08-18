# 部署指南

本文档提供了将通用企业官网部署到生产环境的详细步骤。

## 环境要求

### 服务器要求
- **操作系统**: Linux (Ubuntu 20.04 LTS 或 CentOS 8+ 推荐)
- **内存**: 最低 1GB RAM (推荐 2GB+)
- **磁盘空间**: 至少 5GB 可用空间
- **PHP**: 8.3 或更高版本
- **MySQL**: 5.7 或更高版本 (或 MariaDB 10.4+)
- **Web 服务器**: Nginx 1.18+ 或 Apache 2.4+
- **Node.js**: 20.x 或更高版本 (构建阶段需要)
- **Composer**: 2.5 或更高版本

### PHP 扩展要求
- `pdo_mysql`
- `gd` (用于图像处理)
- `zip`
- `mbstring`
- `xml`
- `curl`
- `openssl`
- `tokenizer`
- `json`
- `ctype`
- `dom`
- `fileinfo`
- `filter`
- `hash`
- `pcre`
- `session`
- `tokenizer`
- `xml`

## 部署步骤

### 1. 服务器准备

#### 安装必要软件包 (Ubuntu 示例)
```bash
# 更新系统包
sudo apt update && sudo apt upgrade -y

# 安装 PHP 8.3 和必需扩展
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install -y php8.3 php8.3-cli php8.3-common php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath php8.3-json php8.3-opcache php8.3-imagick

# 安装 Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# 安装 Node.js 20.x
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# 安装 MySQL
sudo apt install -y mysql-server
```

### 2. 上传代码

#### 方法一：通过 Git 部署
```bash
# 进入 Web 目录
cd /var/www
git clone <your-repo-url> your-project-name
cd your-project-name
```

#### 方法二：上传压缩包
```bash
# 解压代码包到项目目录
unzip your-project.zip -d /var/www/your-project-name
cd /var/www/your-project-name
```

### 3. 安装 PHP 依赖

```bash
# 安装生产环境依赖（不包括开发依赖）
composer install --optimize-autoloader --no-dev

# 如果遇到权限问题，可能需要临时提升权限
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
```

### 4. 配置环境变量

```bash
# 复制示例配置文件
cp .env.example .env

# 编辑 .env 文件，配置数据库连接等信息
nano .env
```

**重要配置项**：
```
APP_NAME="企业官网"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

ADMIN_PREFIX=manage  # 后台路径，建议修改为非默认值

# 邮件配置（如需）
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# 文件上传配置
UPLOAD_DISK=public  # 或者 oss（如果使用阿里云OSS）

# 缓存配置
CACHE_STORE=redis  # 或 redis/file/array
```

### 5. 生成应用密钥

```bash
php artisan key:generate
```

### 6. 数据库配置

#### 创建数据库
```bash
# 登录 MySQL
mysql -u root -p

# 在 MySQL 命令行中执行
CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'your_db_username'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON your_database_name.* TO 'your_db_username'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### 执行数据库迁移
```bash
# 执行数据库迁移
php artisan migrate --force

# 填充初始数据（可选）
php artisan db:seed
```

### 7. 构建前端资源

```bash
# 安装前端依赖
npm install

# 构建生产版本
npm run build
```

### 8. 设置目录权限

```bash
# 设置存储目录权限
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache
sudo chmod -R 755 public/storage  # 如果已创建软链接
```

### 9. 配置 Web 服务器

#### Nginx 配置示例

创建配置文件 `/etc/nginx/sites-available/your-site`：

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/your-project-name/public;
    index index.php index.html index.htm;

    # 安全头设置
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: blob:; font-src 'self'; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self';" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Gzip 压缩
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types
        text/plain
        text/css
        text/xml
        text/javascript
        application/json
        application/javascript
        application/xml+rss
        application/atom+xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }

    # 保护敏感文件
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # 限制上传文件大小
    client_max_body_size 10M;
}
```

启用站点：
```bash
sudo ln -s /etc/nginx/sites-available/your-site /etc/nginx/sites-enabled/
sudo nginx -t  # 检查配置文件语法
sudo systemctl reload nginx
```

#### Apache 配置示例（如果使用）

在虚拟主机配置中：
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/your-project-name/public

    <Directory /var/www/your-project-name/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # 安全头设置
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "no-referrer-when-downgrade"
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### 10. 配置 HTTPS (推荐)

使用 Let's Encrypt 获取免费 SSL 证书：

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### 11. 配置定时任务

添加 Cron 任务以运行 Laravel 调度器：

```bash
crontab -e
```

添加以下行：
```
* * * * * cd /var/www/your-project-name && php artisan schedule:run >> /dev/null 2>&1
```

### 12. 服务配置 (可选)

#### PHP-FPM 优化配置

编辑 `/etc/php/8.3/fpm/pool.d/www.conf`：
```
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
```

重启 PHP-FPM：
```bash
sudo systemctl restart php8.3-fpm
```

## 性能优化

### OPcache 配置

编辑 `/etc/php/8.3/fpm/conf.d/10-opcache.ini`：
```
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### Laravel 优化

运行以下命令优化性能：
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 安全注意事项

1. **文件权限**: 确保敏感文件不被 Web 服务器直接访问
2. **数据库安全**: 使用强密码，限制数据库用户权限
3. **定期更新**: 定期更新系统包、PHP、Composer 包
4. **备份策略**: 定期备份数据库和上传文件
5. **日志监控**: 监控应用日志和服务器日志

## 常见问题排查

### 1. 权限错误
```bash
# 检查存储目录权限
ls -la storage/
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

### 2. URL 重写问题
- Nginx: 确保配置了正确的 `try_files` 指令
- Apache: 确保启用了 `mod_rewrite` 并配置了 `.htaccess`

### 3. 内存不足错误
在 `.env` 中增加内存限制：
```
PHP_MEMORY_LIMIT=512M
```

或在 PHP 配置中：
```
memory_limit = 512M
```

### 4. 时区问题
在 `.env` 中设置：
```
APP_TIMEZONE=Asia/Shanghai
```

## 维护命令

常用维护命令：

```bash
# 清除缓存
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 重建缓存
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 生成旧图片缩略图
php artisan media:generate-thumbs

# 检查应用健康状态
php artisan ide-helper:generate  # 如果安装了 ide-helper
```

## 备份与恢复

### 备份脚本示例
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/your-project-$DATE"

mkdir -p $BACKUP_DIR

# 备份数据库
mysqldump -u your_db_username -p'your_password' your_database_name > $BACKUP_DIR/database.sql

# 备份代码和上传文件
tar -czf $BACKUP_DIR/files.tar.gz /var/www/your-project-name

echo "Backup completed: $BACKUP_DIR"
```

### 恢复步骤
1. 恢复数据库：`mysql -u username -p database_name < backup.sql`
2. 恢复代码文件：`tar -xzf files.tar.gz -C /var/www/`
3. 设置正确的文件权限
