<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $categories;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = User::factory()->create();

        // カテゴリを作成
        $this->categories = Category::factory()->count(3)->create();
    }

    /** @test */
    public function 商品出品画面で必要な情報が保存できる()
    {
        $image = UploadedFile::fake()->image('test.jpg');

        $response = $this->actingAs($this->user)
            ->post('/sell', [
                'name' => 'テスト商品',
                'description' => '商品の説明文です',
                'price' => 1000,
                'condition' => 'good',
                'brand_name' => null,
                'categories' => $this->categories->pluck('id')->toArray(),
                'image' => $image,
            ]);

        $response->assertStatus(302)
            ->assertRedirect(route('items.show', ['item' => Item::first()]));

        // データベースに保存されていることを確認
        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => '商品の説明文です',
            'price' => 1000,
            'condition' => 'good',
            'user_id' => $this->user->id,
        ]);

        // カテゴリが正しく関連付けられていることを確認
        $item = \App\Models\Item::where('name', 'テスト商品')->first();
        foreach ($this->categories as $category) {
            $this->assertDatabaseHas('item_category', [
                'item_id' => $item->id,
                'category_id' => $category->id,
            ]);
        }

        // 画像がアップロードされていることを確認
        $item = Item::first();
        $imagePath = str_replace('/storage/', '', $item->image);
        Storage::disk('public')->assertExists($imagePath);
    }

    /** @test */
    public function 必須項目が未入力の場合はエラーになる()
    {
        $response = $this->actingAs($this->user)
            ->post('/sell', [
                'name' => '',
                'description' => '',
                'price' => '',
                'condition' => '',
                'categories' => [],
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['name', 'description', 'price', 'condition', 'categories', 'image']);
    }

    /** @test */
    public function 未認証ユーザーは商品を出品できない()
    {
        $response = $this->post('/sell', [
            'name' => 'テスト商品',
            'description' => '商品の説明文です',
            'price' => 1000,
            'condition' => 'good',
            'categories' => $this->categories->pluck('id')->toArray(),
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/login');
    }
}
