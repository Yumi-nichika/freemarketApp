<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

/**
 * 配送先変更機能
 * .env.testingにstripe決済に必要なキーを記述してから実行
 */
class AddressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 特定のSeederのみを実行
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    }

    //配送先変更テスト
    public function test_change_address()
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

        //配送先変更ページにアクセス
        $response = $this->get("/purchase/address/{$item->id}");
        $response->assertStatus(200);

        //住所登録
        $response = $this->post("/purchase/address/{$item->id}", [
            'post_code' => '200-3333',
            'address' => '愛知県',
            'building' => 'マンション'
        ]);

        //リダイレクトされたことを確認
        $response->assertStatus(302);
        $response->assertRedirect("/purchase/{$item->id}");

        //リダイレクト先の詳細ページにアクセス
        $response = $this->followRedirects($response);

        //データベース登録確認
        $this->assertDatabaseHas('user_profiles', ['user_id' => $user2->id]);

        //画面表示確認
        $response->assertSee('200-3333');
        $response->assertSee('愛知県');
        $response->assertSee('マンション');
    }

    //配送先紐づけテスト
    public function test_sold_item_link_address()
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

        //配送先変更ページにアクセス
        $response = $this->get("/purchase/address/{$item->id}");
        $response->assertStatus(200);

        //住所登録
        $response = $this->post("/purchase/address/{$item->id}", [
            'post_code' => '200-3333',
            'address' => '愛知県',
            'building' => 'マンション'
        ]);

        //リダイレクトされたことを確認
        $response->assertStatus(302);
        $response->assertRedirect("/purchase/{$item->id}");

        //リダイレクト先の詳細ページにアクセス
        $response = $this->followRedirects($response);

        //データベース登録確認
        $this->assertDatabaseHas('user_profiles', ['user_id' => $user2->id]);
        $savedProfile = \App\Models\UserProfile::where('user_id', $user2->id)->first();

        //購入
        $formData = [
            'payment_method' => 1,
            'shipping' => [
                'post_code' => $savedProfile->post_code,
                'address'   => $savedProfile->address,
                'building'  => $savedProfile->building,
            ],
            'item_name' => $item->item_name,
            'price' => $item->price,
        ];
        $response = $this->post("/purchase/{$item->id}", $formData);

        $response->assertStatus(302);

        //データベース登録確認
        $this->assertDatabaseHas('sold_items', [
            'item_id' => $item->id,
            'user_id' => $user2->id,
            'post_code' => '200-3333',
            'address' => '愛知県',
            'building' => 'マンション'
        ]);
    }
}
