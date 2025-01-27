<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $otherUser;
    private $item;
    private $soldItem;
    private $myItem;
    private $likedItem;

    public function setUp(): void
    {
        parent::setUp();

        // テストユーザーを作成
        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();

        // 通常の商品を作成
        $this->item = Item::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => '通常商品',
        ]);

        // 購入済み商品を作成
        $this->soldItem = Item::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => '購入済み商品',
            'is_sold' => true,
        ]);
        Purchase::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->soldItem->id,
        ]);

        // 自分の出品商品を作成
        $this->myItem = Item::factory()->create([
            'user_id' => $this->user->id,
            'name' => '自分の商品',
        ]);

        // いいねした商品を作成
        $this->likedItem = Item::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'いいねした商品',
        ]);
        Like::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->likedItem->id,
        ]);
    }

    /** @test */
    public function 全ての商品が表示される()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('通常商品');
        $response->assertSee('購入済み商品');
        $response->assertSee('いいねした商品');
    }

    /** @test */
    public function 購入済み商品にはSoldと表示される()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function ログイン時に自分の出品した商品は表示されない()
    {
        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
    }

    /** @test */
    public function マイリストではいいねした商品のみが表示される()
    {
        $response = $this->actingAs($this->user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('通常商品');
    }

    /** @test */
    public function マイリストで購入済み商品にはSoldと表示される()
    {
        Like::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->soldItem->id,
        ]);

        $response = $this->actingAs($this->user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function マイリストで自分の出品した商品は表示されない()
    {
        Like::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->myItem->id,
        ]);

        $response = $this->actingAs($this->user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
    }

    /** @test */
    public function 未認証ユーザーのマイリストでは何も表示されない()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('いいねした商品');
        $response->assertDontSee('通常商品');
        $response->assertDontSee('購入済み商品');
        $response->assertDontSee('自分の商品');
    }
}
