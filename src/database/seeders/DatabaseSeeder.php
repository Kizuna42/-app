<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * シーディングを実行
     */
    public function run(): void
    {
        $this->call([
            ItemSeeder::class,
        ]);
    }
}
