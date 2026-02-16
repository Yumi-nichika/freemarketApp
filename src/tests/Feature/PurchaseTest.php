<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Item;
use App\Models\Category;

/**
 * 商品購入機能
 * .env.testingにstripe決済に必要なキーを記述してから実行
 */
class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 特定のSeederのみを実行
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    }

    //購入テスト
    public function test_buy_item()
    {
        //ユーザー作成
        $user1 = User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'テスト花子',
            'email' => 'test2@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user_profile = UserProfile::factory()->create(['user_id' => $user2->id]);

        //出品商品作成
        $categories = Category::all();
        $selectedCategory = $categories->random();
        $item = Item::factory()->hasAttached($selectedCategory)->create(['user_id' => $user1->id]);

        //user2でログイン
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user2);

        //商品購入ページにアクセス
        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        //購入
        $formData = [
            'payment_method' => 1,
            'shipping' => [
                'post_code' => $user_profile->post_code,
                'address'   => $user_profile->address,
                'building'  => $user_profile->building,
            ],
            'item_name' => $item->item_name,
            'price' => $item->price,
        ];
        $response = $this->post("/purchase/{$item->id}", $formData);

        $response->assertStatus(302);

        //データベース登録確認
        $this->assertDatabaseHas('sold_items', ['item_id' => $item->id, 'user_id' => $user2->id]);
    }

    //購入後の商品一覧表示テスト
    public function test_buy_item_show_list()
    {
        //ユーザー作成
        $user1 = User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'テスト花子',
            'email' => 'test2@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user_profile = UserProfile::factory()->create(['user_id' => $user2->id]);

        //出品商品作成
        $categories = Category::all();
        $selectedCategory = $categories->random();
        $item = Item::factory()->hasAttached($selectedCategory)->create(['user_id' => $user1->id]);

        //user2でログイン
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user2);

        //商品一覧ページにアクセス
        $response = $this->get('/');
        $response->assertStatus(200);

        //商品一覧全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list recommend">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $listArea)) {
            $listHtml = $listArea[0];

            //soldの表示なし
            $this->assertStringNotContainsString('sold', $listHtml);
        }

        //商品購入ページにアクセス
        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        //購入
        $formData = [
            'payment_method' => 1,
            'shipping' => [
                'post_code' => $user_profile->post_code,
                'address'   => $user_profile->address,
                'building'  => $user_profile->building,
            ],
            'item_name' => $item->item_name,
            'price' => $item->price,
        ];
        $response = $this->post("/purchase/{$item->id}", $formData);

        $response->assertStatus(302);

        //データベース登録確認
        $this->assertDatabaseHas('sold_items', ['item_id' => $item->id, 'user_id' => $user2->id]);

        //商品一覧ページにアクセス
        $response = $this->get('/');
        $response->assertStatus(200);

        //商品一覧全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list recommend">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $listArea)) {
            $listHtml = $listArea[0];

            //soldの表示あり
            $this->assertStringContainsString('sold', $listHtml);
        }
    }

    //購入後の購入した商品一覧表示テスト
    public function test_buy_item_show_buy_list()
    {
        //ユーザー作成
        $user1 = User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'テスト花子',
            'email' => 'test2@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user_profile = UserProfile::factory()->create(['user_id' => $user2->id]);

        //出品商品作成
        $categories = Category::all();
        $selectedCategory = $categories->random();
        $item = Item::factory()->hasAttached($selectedCategory)->create(['user_id' => $user1->id]);

        //user2でログイン
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user2);

        //購入した商品一覧ページにアクセス
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);

        //購入した商品一覧全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list buy">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $listArea)) {
            $listHtml = $listArea[0];

            //商品なし
            $this->assertStringNotContainsString('<div class="item">', $listHtml);
        }

        // ビューに渡された変数の件数確認
        $response->assertViewHas('sold_items', function ($sold_items) {
            return $sold_items->count() === 0;
        });

        //商品購入ページにアクセス
        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        //購入
        $formData = [
            'payment_method' => 1,
            'shipping' => [
                'post_code' => $user_profile->post_code,
                'address'   => $user_profile->address,
                'building'  => $user_profile->building,
            ],
            'item_name' => $item->item_name,
            'price' => $item->price,
        ];
        $response = $this->post("/purchase/{$item->id}", $formData);

        $response->assertStatus(302);

        //データベース登録確認
        $this->assertDatabaseHas('sold_items', ['item_id' => $item->id, 'user_id' => $user2->id]);

        //購入した商品一覧ページにアクセス
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);

        //購入した商品一覧全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list buy">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $listArea)) {
            $listHtml = $listArea[0];

            //商品あり
            $this->assertStringContainsString('<div class="item">', $listHtml);
            $this->assertStringContainsString($item->item_name, $listHtml);
        }

        // ビューに渡された変数の件数確認
        $response->assertViewHas('sold_items', function ($sold_items) {
            return $sold_items->count() === 1;
        });
    }
}
