FROM php:8.3-cli

# 配置国内镜像源
RUN sed -i 's/deb.debian.org/mirrors.aliyun.com/g' /etc/apt/sources.list.d/debian.sources || \
    echo "deb http://mirrors.aliyun.com/debian trixie main" > /etc/apt/sources.list && \
    echo "deb http://mirrors.aliyun.com/debian trixie-updates main" >> /etc/apt/sources.list && \
    echo "deb http://mirrors.aliyun.com/debian-security trixie-security main" >> /etc/apt/sources.list

# 安装系统依赖
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    zip \
    unzip \
    nano \
    supervisor

# 清理包管理器缓存
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 安装 PHP 扩展
RUN docker-php-ext-configure gd --enable-gd --with-jpeg --with-freetype
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 安装 GD 扩展的额外库

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 创建工作目录
WORKDIR /var/www

# 复制应用程序代码
COPY . /var/www

# 设置正确的权限
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www
RUN chmod -R 775 /var/www/storage
RUN chmod -R 775 /var/www/bootstrap/cache

# 安装 PHP 依赖
RUN composer install

# 安装 Node.js 和 npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 安装前端依赖并构建
RUN npm install
RUN npm run build

# 暴露端口
EXPOSE 8000

# 启动 Laravel 开发服务器
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]