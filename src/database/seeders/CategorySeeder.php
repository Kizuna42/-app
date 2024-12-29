<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * シーディングを実行
     */
    public function run(): void
    {
        $categories = [
            'ファッション' => [
                'メンズ',
                'レディース',
                'キッズ',
            ],
            '家電' => [
                'スマートフォン',
                'パソコン',
                'オーディオ',
                'カメラ',
            ],
            'インテリア・住まい' => [
                '家具',
                'キッチン',
                '照明',
            ],
            'その他' => [],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
            ]);

            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
} 