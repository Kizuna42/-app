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

        // カテゴリーを取得
        $fashion = Category::where('name', 'ファッション')->first();
        $electronics = Category::where('name', '家電')->first();
        $interior = Category::where('name', 'インテリア')->first();
        $mens = Category::where('name', 'メンズ')->first();
        $ladies = Category::where('name', 'レディース')->first();
        $accessory = Category::where('name', 'アクセサリー')->first();
        $kitchen = Category::where('name', 'キッチン')->first();

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => 'good',
                'categories' => [$fashion->id, $mens->id, $accessory->id],
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => 'good',
                'categories' => [$electronics->id],
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition' => 'fair',
                'categories' => [$kitchen->id],
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition' => 'poor',
                'categories' => [$fashion->id, $mens->id],
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition' => 'good',
                'categories' => [$electronics->id],
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition' => 'good',
                'categories' => [$electronics->id],
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition' => 'fair',
                'categories' => [$fashion->id, $ladies->id, $accessory->id],
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition' => 'poor',
                'categories' => [$interior->id],
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition' => 'good',
                'categories' => [$interior->id],
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition' => 'good',
                'categories' => [$kitchen->id, $ladies->id, $accessory->id],
            ],
        ];

        foreach ($items as $itemData) {
            $categories = $itemData['categories'];
            unset($itemData['categories']);

            $item = Item::create([
                'user_id' => $user->id,
                'name' => $itemData['name'],
                'price' => $itemData['price'],
                'description' => $itemData['description'],
                'image' => $itemData['image'],
                'condition' => $itemData['condition'],
                'is_sold' => false,
            ]);

            // カテゴリーを関連付け
            $item->categories()->attach($categories);
        }
    }
}