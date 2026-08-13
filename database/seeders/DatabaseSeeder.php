<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 创建管理员账户
        User::create([
            'name' => '管理员',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
        ]);

        // 站点配置
        $this->call([
            SiteSettingSeeder::class,
        ]);
    }
}
