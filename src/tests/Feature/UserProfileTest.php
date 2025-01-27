<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $listedItems;
    private $purchasedItems;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // テストユーザーを作成
        $this->user = User::factory()->create([
            'name' => 'テストユーザー',
            'avatar' => 'avatars/test.jpg',
            'postal_code' => '1234567',
            'address' => '東京都渋谷区道玄坂1-1-1',
            'building_name' => 'テストビル101',
        ]);

        // 出品した商品を作成
        $this->listedItems = Item::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        // 購入した商品を作成
        $seller = User::factory()->create();
        $items = Item::factory()->count(2)->create([
            'user_id' => $seller->id,
            'is_sold' => true,
        ]);

        foreach ($items as $item) {
            Purchase::factory()->create([
                'user_id' => $this->user->id,
                'item_id' => $item->id,
                'price' => $item->price,
            ]);
        }
        $this->purchasedItems = $items;
    }

    /** @test */
    public function プロフィールページに必要な情報が表示される()
    {
        $response = $this->actingAs($this->user)
            ->get('/mypage');

        $response->assertStatus(200)
            ->assertSee($this->user->name)
            ->assertSee('avatars/test.jpg');

        // 出品した商品が表示されることを確認
        foreach ($this->listedItems as $item) {
            $response->assertSee($item->name);
        }

        // 購入した商品タブに切り替えて表示を確認
        $response = $this->actingAs($this->user)
            ->get('/mypage?tab=buy');

        $response->assertStatus(200);
        foreach ($this->purchasedItems as $item) {
            $response->assertSee($item->name);
        }
    }

    /** @test */
    public function プロフィール編集画面に初期値が表示される()
    {
        $response = $this->actingAs($this->user)
            ->get('/mypage/profile');

        $response->assertStatus(200)
            ->assertSee($this->user->name)
            ->assertSee($this->user->postal_code)
            ->assertSee($this->user->address)
            ->assertSee($this->user->building_name)
            ->assertSee('avatars/test.jpg');
    }
}
