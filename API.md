# API 文档

本文档详细介绍了企业官网系统的 API 接口。

## 概述

企业官网系统提供 RESTful API 接口，主要用于后台管理系统与前端的交互。

### 基础信息
- **API 版本**: v1
- **协议**: HTTPS (推荐)
- **内容类型**: application/json
- **认证方式**: Session Cookie (后台) 或 API Token (未来扩展)

### 响应格式

所有 API 响应遵循以下格式：

```json
{
  "data": {},
  "message": "操作成功",
  "status_code": 200
}
```

成功时 HTTP 状态码为 2xx，失败时为 4xx 或 5xx。

## 认证 API

### 登录
- **URL**: `POST /manage/login`
- **描述**: 管理员登录
- **请求参数**:
  ```json
  {
    "login": "用户名或邮箱",
    "password": "密码",
    "remember": false
  }
  ```
- **响应**:
  ```json
  {
    "message": "登录成功",
    "redirectTo": "/manage/dashboard"
  }
  ```

### 登出
- **URL**: `POST /manage/logout`
- **描述**: 管理员登出
- **响应**:
  ```json
  {
    "message": "已退出登录"
  }
  ```

## 内容管理 API

### 产品管理

#### 获取产品列表
- **URL**: `GET /manage/api/products`
- **参数**:
  - `page`: 页码 (可选, 默认 1)
  - `per_page`: 每页数量 (可选, 默认 20)
  - `category_id`: 分类ID (可选)
  - `search`: 搜索关键词 (可选)
- **响应**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "title": "产品标题",
        "slug": "product-slug",
        "thumbnail": "/storage/path/to/thumb.jpg",
        "summary": "产品简介",
        "content": "产品详情",
        "status": "active",
        "sort": 0,
        "is_featured": false,
        "category_id": 1,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    ],
    "links": {
      "first": "...",
      "last": "...",
      "prev": null,
      "next": "..."
    },
    "meta": {
      "current_page": 1,
      "from": 1,
      "last_page": 5,
      "path": "...",
      "per_page": 20,
      "to": 20,
      "total": 100
    }
  }
  ```

#### 创建产品
- **URL**: `POST /manage/api/products`
- **请求参数**:
  ```json
  {
    "title": "产品标题",
    "slug": "product-slug",
    "thumbnail": "文件对象或路径",
    "summary": "产品简介",
    "content": "产品详情",
    "status": "active",
    "sort": 0,
    "is_featured": false,
    "category_id": 1
  }
  ```

#### 更新产品
- **URL**: `PUT /manage/api/products/{id}`
- **请求参数**: 同创建产品

#### 删除产品
- **URL**: `DELETE /manage/api/products/{id}`
- **响应**:
  ```json
  {
    "message": "删除成功"
  }
  ```

### 文章管理

#### 获取文章列表
- **URL**: `GET /manage/api/posts`
- **参数**:
  - `page`: 页码 (可选, 默认 1)
  - `per_page`: 每页数量 (可选, 默认 20)
  - `category_id`: 分类ID (可选)
  - `search`: 搜索关键词 (可选)
- **响应**: 类似产品列表

#### 创建/更新/删除文章
- **URL**: `POST/PUT/DELETE /manage/api/posts[/{id}]`
- **参数**: 类似产品管理

### 分类管理

#### 获取分类列表
- **URL**: `GET /manage/api/categories`
- **参数**:
  - `type`: 分类类型 ('post' 或 'product')
- **响应**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "name": "分类名称",
        "slug": "category-slug",
        "type": "post",
        "is_active": true,
        "sort": 0,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    ]
  }
  ```

### 页面管理

#### 获取页面列表
- **URL**: `GET /manage/api/pages`
- **响应**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "title": "页面标题",
        "slug": "page-slug",
        "seo_title": "SEO 标题",
        "seo_description": "SEO 描述",
        "content": "页面内容",
        "is_active": true,
        "sort": 0,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    ]
  }
  ```

### 首页板块管理

#### 获取板块列表
- **URL**: `GET /manage/api/homepage-sections`
- **响应**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "title": "板块标题",
        "type": "hero",
        "content": "板块内容",
        "extra": {},
        "is_active": true,
        "sort": 0,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    ]
  }
  ```

### 留言管理

#### 获取留言列表
- **URL**: `GET /manage/api/messages`
- **参数**:
  - `page`: 页码 (可选, 默认 1)
  - `per_page`: 每页数量 (可选, 默认 20)
  - `is_read`: 是否已读 (可选, true/false)
- **响应**:
  ```json
  {
    "data": [
      {
        "id": 1,
        "name": "留言者姓名",
        "email": "email@example.com",
        "phone": "电话号码",
        "subject": "主题",
        "message": "留言内容",
        "is_read": false,
        "replied_at": null,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
      }
    ]
  }
  ```

#### 标记留言为已读
- **URL**: `PUT /manage/api/messages/{id}/mark-as-read`
- **响应**:
  ```json
  {
    "message": "标记成功"
  }
  ```

#### 删除留言
- **URL**: `DELETE /manage/api/messages/{id}`

### 站点设置

#### 获取站点设置
- **URL**: `GET /manage/api/settings`
- **响应**:
  ```json
  {
    "data": {
      "site_name": "站点名称",
      "site_slogan": "站点标语",
      "site_description": "站点描述",
      "contact_phone": "联系电话",
      "contact_email": "联系邮箱",
      "contact_address": "联系地址",
      "seo_keywords": "SEO 关键词",
      "seo_description": "SEO 描述",
      "theme_primary_color": "#2563eb",
      "logo_path": "/storage/path/to/logo.png",
      "favicon_path": "/storage/path/to/favicon.png"
    }
  }
  ```

#### 更新站点设置
- **URL**: `PUT /manage/api/settings`
- **请求参数**:
  ```json
  {
    "site_name": "新站点名称",
    "site_slogan": "新站点标语",
    "contact_phone": "新联系电话",
    "logo": "文件对象或路径"
  }
  ```

## 公共 API

### 获取产品列表（前台）
- **URL**: `GET /api/products`
- **参数**:
  - `category`: 分类slug (可选)
  - `q`: 搜索关键词 (可选)
  - `page`: 页码 (可选)
- **响应**:
  ```json
  {
    "data": [...],
    "links": {...},
    "meta": {...}
  }
  ```

### 获取文章列表（前台）
- **URL**: `GET /api/posts`
- **参数**: 类似产品列表
- **响应**: 类似产品列表

### 提交留言
- **URL**: `POST /contact`
- **请求参数**:
  ```json
  {
    "name": "姓名",
    "email": "邮箱",
    "phone": "电话 (可选)",
    "subject": "主题 (可选)",
    "message": "留言内容",
    "website": ""  // honeypot 字段，机器人填写会导致静默丢弃
  }
  ```
- **响应**:
  ```json
  {
    "message": "留言已提交，我们会尽快与您联系！"
  }
  ```

## 上传 API

### 图片上传
- **URL**: `POST /manage/upload`
- **参数**:
  - `wangeditor-uploaded-image`: 文件对象
  - 或 `file`: 文件对象
  - 或 `image`: 文件对象
- **响应**:
  ```json
  {
    "errno": 0,
    "data": {
      "url": "/storage/path/to/image.jpg"
    }
  }
  ```

## 错误码

| 状态码 | 描述 |
|--------|------|
| 200 | 成功 |
| 400 | 请求错误 |
| 401 | 未认证 |
| 403 | 禁止访问 |
| 404 | 资源不存在 |
| 422 | 验证失败 |
| 429 | 请求过多 |
| 500 | 服务器错误 |

## 速率限制

系统对以下操作实施速率限制：
- 登录尝试：5次/分钟
- 留言提交：3次/分钟
- 文件上传：根据具体配置

## 安全说明

- 所有 API 请求都需要有效的会话或适当的认证
- 文件上传限制为图像格式（jpg, jpeg, png, gif, webp）
- 所有输入都会经过验证和清理
- 实施了 CSRF 保护