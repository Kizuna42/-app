<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
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
    public function 支払い方法が即時に反映される()
    {
        $response = $this->actingAs($this->user)
            ->post(route('purchases.update-payment', $this->item), [
                'payment_method' => 'credit_card'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'payment_method' => 'credit_card'
            ]);
    }

    /** @test */
    public function 未認証ユーザーは支払い方法を変更できない()
    {
        $response = $this->post(route('purchases.update-payment', $this->item), [
            'payment_method' => 'credit_card'
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/login');
    }

    /** @test */
    public function 不正な支払い方法は選択できない()
    {
        $response = $this->actingAs($this->user)
            ->post(route('purchases.update-payment', $this->item), [
                'payment_method' => 'invalid_method'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payment_method']);
    }
}
