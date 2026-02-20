<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Item;
use App\Models\Category;

/**
 * 支払い方法選択機能
 */
class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 特定のSeederのみを実行
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    }

    //支払い方法に「コンビニ支払い」を選択テスト
    public function test_select_payment_method_konbini()
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

        //コンビニ選択
        $response = $this->get("/purchase/{$item->id}?select_method=1");
        $response->assertStatus(200);

        //セレクトボックスが選択状態か確認
        $response->assertSee('value="1" selected', false);

        //テキストが反映されているか確認
        $response->assertSee('コンビニ支払い');
    }

    //支払い方法に「カード支払い」を選択テスト
    public function test_select_payment_method_card()
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

        //カード選択
        $response = $this->get("/purchase/{$item->id}?select_method=2");
        $response->assertStatus(200);

        //セレクトボックスが選択状態か確認
        $response->assertSee('value="2" selected', false);

        //テキストが反映されているか確認
        $response->assertSee('カード支払い');
    }
}
