<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Upload Disk（后台上传切换开关）
    |--------------------------------------------------------------------------
    |
    | 后台图片上传（富文本 / 封面 / 缩略图）使用的存储磁盘。默认 `public`
    | 走本地 + storage:link 软链。可改为 `oss` 切换至阿里云 OSS（需先在
    | 下方 disks.oss 与 .env 中填好 OSS_* 配置）。切换只改 .env 一处即可。
    |
    */
    'upload_disk' => env('UPLOAD_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

        // 阿里云 OSS（走 S3 兼容协议，无需额外 SDK）。默认不启用，
        // 当 .env 中 UPLOAD_DISK=oss 并填好下方 OSS_* 配置后生效。
        // 注意：OSS bucket 需设为「公共读」，或改用 Storage::temporaryUrl 生成签名地址。
        'oss' => [
            'driver' => 's3',
            'key' => env('OSS_ACCESS_KEY_ID'),
            'secret' => env('OSS_ACCESS_KEY_SECRET'),
            'region' => env('OSS_REGION', 'cn-hangzhou'),
            'bucket' => env('OSS_BUCKET'),
            'endpoint' => env('OSS_ENDPOINT'),
            'url' => env('OSS_URL'),
            'use_path_style_endpoint' => env('OSS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
