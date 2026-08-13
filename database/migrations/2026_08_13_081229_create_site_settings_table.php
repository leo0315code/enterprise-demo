<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general')->index()->comment('设置分组');
            $table->string('key')->unique()->comment('配置键');
            $table->text('value')->nullable()->comment('配置值');
            $table->string('label')->nullable()->comment('显示名称');
            $table->string('type')->default('text')->comment('字段类型: text/textarea/image/switch/select');
            $table->string('description')->nullable()->comment('描述说明');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
