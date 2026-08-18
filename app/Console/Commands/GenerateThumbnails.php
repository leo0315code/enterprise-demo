<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Product;
use App\Services\Thumbnail;
use Illuminate\Console\Command;

/**
 * 为旧数据回填列表缩略图。
 * 新上传的图片在上传时已自动生成，此命令用于补齐历史内容。
 */
class GenerateThumbnails extends Command
{
    protected $signature = 'media:generate-thumbs {--force : 已存在缩略图也重新生成}';

    protected $description = '为已有文章封面与产品图片生成列表缩略图';

    public function handle(): int
    {
        if (config('filesystems.upload_disk', 'public') !== 'public') {
            $this->warn('当前 UPLOAD_DISK 非本地 public 磁盘，回填命令仅支持本地磁盘。');

            return self::FAILURE;
        }

        $force = (bool) $this->option('force');
        $generated = 0;
        $skipped = 0;
        $failed = 0;

        // 收集所有候选图片 URL：文章封面 + 产品图片
        $urls = Post::query()->whereNotNull('cover')->pluck('cover')
            ->merge(Product::query()->whereNotNull('thumbnail')->pluck('thumbnail'))
            ->unique()
            ->values();

        if ($urls->isEmpty()) {
            $this->info('没有需要处理的图片。');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($urls->count());

        foreach ($urls as $url) {
            $path = Thumbnail::pathFromUrl($url);

            // 外链 / gif / 文件已不存在：跳过
            if (! $path) {
                $skipped++;
                $bar->advance();
                continue;
            }

            try {
                Thumbnail::generate('public', $path, $force) ? $generated++ : $skipped++;
            } catch (\Throwable $e) {
                $failed++;
                $this->newLine();
                $this->warn("生成失败 {$path}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("完成：新生成 {$generated}，跳过 {$skipped}，失败 {$failed}。");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
