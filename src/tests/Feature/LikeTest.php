<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 特定のSeederのみを実行
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    }

    //いいねテスト
    //いいねアイコン切り替わりテスト
    public function test_check_like()
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

        //商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        //いいねする前
        $response->assertSeeInOrder(['img/hart_off.png', '0']);

        //いいね押下
        $response = $this->post("/item/{$item->id}/like", []);

        //リダイレクトされたことを確認
        $response->assertStatus(302);
        $response->assertRedirect("/item/{$item->id}");

        //リダイレクト先の詳細ページにアクセス
        $response = $this->followRedirects($response);

        //データベース登録確認
        $this->assertDatabaseHas('likes', ['item_id' => $item->id, 'user_id' => $user2->id]);

        //いいねした後
        $response->assertSeeInOrder(['img/hart_on.png', '1']);
    }

    //いいね解除テスト
    public function test_checkout_like()
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

        //いいね作成
        $like = Like::factory()->create(['item_id' => $item->id, 'user_id' => $user2->id]);

        //user2でログイン
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user2);

        //商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        //いいね解除する前
        $response->assertSeeInOrder(['img/hart_on.png', '1']);

        //いいね押下
        $response = $this->post("/item/{$item->id}/like", []);

        //リダイレクトされたことを確認
        $response->assertStatus(302);
        $response->assertRedirect("/item/{$item->id}");

        //リダイレクト先の詳細ページにアクセス
        $response = $this->followRedirects($response);

        //データベース削除確認
        $this->assertDatabaseMissing('likes', ['item_id' => $item->id, 'user_id' => $user2->id]);

        //いいね解除した後
        $response->assertSeeInOrder(['img/hart_off.png', '0']);
    }
}
