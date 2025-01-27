<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $item;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->item = Item::factory()->create([
            'price' => 1000,
            'is_sold' => false,
        ]);
    }

    /** @test */
    public function 支払い方法を選択すると即時反映される()
    {
        $response = $this->actingAs($this->user)
            ->get("/purchase/{$this->item->id}");

        $response->assertStatus(200)
            ->assertSee('支払い方法')
            ->assertSee('クレジットカード')
            ->assertSee('コンビニ決済');

        // 支払い方法を選択
        $response = $this->actingAs($this->user)
            ->post("/purchase/{$this->item->id}/payment", [
                'payment_method' => 'credit',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'payment_method' => 'credit',
            ]);

        // 選択した支払い方法が反映されていることを確認
        $response = $this->actingAs($this->user)
            ->get("/purchase/{$this->item->id}");

        $response->assertStatus(200)
            ->assertSee('クレジットカード');
    }

    /** @test */
    public function 未認証ユーザーは支払い方法を選択できない()
    {
        $response = $this->post("/purchase/{$this->item->id}/payment", [
            'payment_method' => 'credit',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    /** @test */
    public function 不正な支払い方法は選択できない()
    {
        $response = $this->actingAs($this->user)
            ->post("/purchase/{$this->item->id}/payment", [
                'payment_method' => 'invalid_method',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payment_method']);
    }
}
