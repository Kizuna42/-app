<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $item;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->item = Item::factory()->create();
    }

    /** @test */
    public function 商品にいいねができる()
    {
        $response = $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'is_liked' => true,
                'likes_count' => 1
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);
    }

    /** @test */
    public function いいね済みの商品のいいねを解除できる()
    {
        // 事前にいいねを作成
        Like::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post("/items/{$this->item->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'is_liked' => false,
                'likes_count' => 0
            ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);
    }

    /** @test */
    public function 未認証ユーザーはいいねができない()
    {
        $response = $this->post("/items/{$this->item->id}/like");

        $response->assertStatus(302)
            ->assertRedirect('/login');

        $this->assertDatabaseMissing('likes', [
            'item_id' => $this->item->id,
        ]);
    }

    /** @test */
    public function いいねした商品の状態を確認できる()
    {
        // いいねを作成
        Like::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/item/{$this->item->id}");

        $response->assertStatus(200);
        $response->assertSee('liked'); // いいねアイコンの状態を示すクラスを確認
    }
}
