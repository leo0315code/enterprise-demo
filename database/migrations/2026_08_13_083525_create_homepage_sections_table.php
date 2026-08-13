<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('板块类型: hero/banner/intro/features/products/news/cta/custom');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('content')->nullable()->comment('富文本内容');
            $table->string('image')->nullable()->comment('背景/配图');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->text('extra')->nullable()->comment('扩展字段 JSON (如卡片列表)');
            $table->integer('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
