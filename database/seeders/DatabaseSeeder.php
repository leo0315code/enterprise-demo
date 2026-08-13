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
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => '管理员',
                'password' => Hash::make('admin123'),
            ]
        );

        $this->call([
            SiteSettingSeeder::class,
            PageSeeder::class,
            HomepageSectionSeeder::class,
        ]);
    }
}
