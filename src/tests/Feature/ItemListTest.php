<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Category;
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

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        // テスト用の商品を作成
        Item::factory()->create(['name' => 'テスト商品ABC']);
        Item::factory()->create(['name' => 'サンプル商品XYZ']);

        // 検索を実行
        $response = $this->get('/?search=ABC');

        $response->assertStatus(200);
        $response->assertSee('テスト商品ABC');
        $response->assertDontSee('サンプル商品XYZ');
    }

    /** @test */
    public function マイリストページでも検索キーワードが保持されている()
    {
        // いいねした商品を作成
        $item = Item::factory()->create(['name' => 'いいねした商品ABC']);
        Like::factory()->create([
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);

        // 検索を実行
        $response = $this->actingAs($this->user)
            ->get('/?tab=mylist&search=ABC');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品ABC');
        $response->assertSee('value="ABC"', false); // 検索フォームに値が保持されている
    }

    /** @test */
    public function 複数選択されたカテゴリの商品が表示される()
    {
        // カテゴリを作成
        $category1 = Category::factory()->create(['name' => 'カテゴリ1']);
        $category2 = Category::factory()->create(['name' => 'カテゴリ2']);

        // カテゴリに属する商品を作成
        $item1 = Item::factory()->create(['name' => 'カテゴリ1の商品']);
        $item1->categories()->attach($category1->id);

        $item2 = Item::factory()->create(['name' => 'カテゴリ2の商品']);
        $item2->categories()->attach($category2->id);

        // 商品詳細ページにアクセス
        $response = $this->get("/item/{$item1->id}");

        $response->assertStatus(200);
        $response->assertSee('カテゴリ1');

        // 商品一覧ページでカテゴリでフィルタ
        $response = $this->get('/?category=' . $category1->id);

        $response->assertStatus(200);
        $response->assertSee('カテゴリ1の商品');
        $response->assertDontSee('カテゴリ2の商品');
    }

    /** @test */
    public function 商品詳細ページに必要な情報が全て表示される()
    {
        // コメントを持つ商品を作成
        $user = User::factory()->create(['name' => 'コメントユーザー']);
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 1000,
            'description' => '商品の説明文',
            'condition' => 'good',
        ]);

        // カテゴリを追加
        $category1 = Category::factory()->create(['name' => 'カテゴリ1']);
        $category2 = Category::factory()->create(['name' => 'カテゴリ2']);
        $item->categories()->attach([$category1->id, $category2->id]);

        // いいねを追加
        Like::factory()->count(3)->create(['item_id' => $item->id]);

        // コメントを追加
        $item->comments()->create([
            'user_id' => $user->id,
            'content' => 'テストコメント'
        ]);

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSee('テスト商品'); // 商品名
        $response->assertSee('テストブランド'); // ブランド名
        $response->assertSee('1,000'); // 価格
        $response->assertSee('商品の説明文'); // 商品説明
        $response->assertSee('目立った傷や汚れなし'); // 商品の状態
        $response->assertSee('カテゴリ1'); // カテゴリ1
        $response->assertSee('カテゴリ2'); // カテゴリ2
        $response->assertSee('3'); // いいね数
        $response->assertSee('1'); // コメント数
        $response->assertSee('コメントユーザー'); // コメントユーザー名
        $response->assertSee('テストコメント'); // コメント内容
    }

    /** @test */
    public function 商品詳細ページで複数のカテゴリが表示される()
    {
        $item = Item::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get("/item/{$item->id}");

        $response->assertStatus(200);
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}
