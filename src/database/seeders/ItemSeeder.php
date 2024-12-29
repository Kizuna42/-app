<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * シーディングを実行
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();
        
        // カテゴリーIDを取得
        $fashionId = Category::where('name', 'ファッション')->first()->id;
        $electronicsId = Category::where('name', '家電')->first()->id;
        $interiorId = Category::where('name', 'インテリア・住まい')->first()->id;
        $otherId = Category::where('name', 'その他')->first()->id;

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'items/Armani+Mens+Clock.jpg',
                'condition' => 'good',
                'category_id' => $fashionId,
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'items/HDD+Hard+Disk.jpg',
                'condition' => 'good',
                'category_id' => $electronicsId,
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'items/iLoveIMG+d.jpg',
                'condition' => 'fair',
                'category_id' => $otherId,
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'image' => 'items/Leather+Shoes+Product+Photo.jpg',
                'condition' => 'poor',
                'category_id' => $fashionId,
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'image' => 'items/Living+Room+Laptop.jpg',
                'condition' => 'good',
                'category_id' => $electronicsId,
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'image' => 'items/Music+Mic+4632231.jpg',
                'condition' => 'good',
                'category_id' => $electronicsId,
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'items/Purse+fashion+pocket.jpg',
                'condition' => 'fair',
                'category_id' => $fashionId,
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'image' => 'items/Tumbler+souvenir.jpg',
                'condition' => 'poor',
                'category_id' => $interiorId,
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'image' => 'items/Waitress+with+Coffee+Grinder.jpg',
                'condition' => 'good',
                'category_id' => $interiorId,
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'image' => 'items/外出メイクアップセット.jpg',
                'condition' => 'good',
                'category_id' => $otherId,
            ],
        ];

        foreach ($items as $item) {
            Item::create([
                'user_id' => $user->id,
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => $item['description'],
                'image' => $item['image'],
                'condition' => $item['condition'],
                'category_id' => $item['category_id'],
                'is_sold' => false,
            ]);
        }
    }
} 