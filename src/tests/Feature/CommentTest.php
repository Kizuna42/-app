<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
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
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $response = $this->actingAs($this->user)
            ->post("/item/{$this->item->id}/comment", [
                'content' => 'テストコメント'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'content' => 'テストコメント'
        ]);

        // コメント数が増加していることを確認
        $this->assertEquals(1, $this->item->fresh()->comments()->count());
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない()
    {
        $response = $this->post("/item/{$this->item->id}/comment", [
            'content' => 'テストコメント'
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $this->item->id,
            'content' => 'テストコメント'
        ]);
    }

    /** @test */
    public function コメントが入力されていない場合バリデーションメッセージが表示される()
    {
        $response = $this->actingAs($this->user)
            ->post("/item/{$this->item->id}/comment", [
                'content' => ''
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function コメントが255字以上の場合バリデーションメッセージが表示される()
    {
        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($this->user)
            ->post("/item/{$this->item->id}/comment", [
                'content' => $longComment
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }
}
