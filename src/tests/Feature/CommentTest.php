<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Item;
use App\Models\Category;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 特定のSeederのみを実行
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    }

    //コメント送信テスト
    public function test_send_comment()
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

        //商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        //コメントする前
        $response->assertSeeInOrder(['img/fukidashi.png', '0']);
        $response->assertSee('コメント(0)');

        //コメント送信
        $response = $this->post("/item/{$item->id}/comment", ['comment' => 'テストコメントです。']);

        //リダイレクトされたことを確認
        $response->assertStatus(302);
        $response->assertRedirect("/item/{$item->id}");

        //リダイレクト先の詳細ページにアクセス
        $response = $this->followRedirects($response);

        //データベース登録確認
        $this->assertDatabaseHas('comments', ['item_id' => $item->id, 'user_id' => $user2->id, 'comment' => 'テストコメントです。']);

        //コメントした後
        $response->assertSeeInOrder(['img/fukidashi.png', '1']);
        $response->assertSee('コメント(1)');
    }

    //コメント送信テスト（未ログイン）
    public function test_send_comment_when_not_authenticated()
    {
        //ユーザー作成
        $user1 = User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        //出品商品作成
        $categories = Category::all();
        $selectedCategory = $categories->random();
        $item = Item::factory()->hasAttached($selectedCategory)->create(['user_id' => $user1->id]);

        //商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        //コメント送信
        $response = $this->post("/item/{$item->id}/comment", ['comment' => 'テストコメントです。']);

        //データベース未登録確認
        $this->assertDatabaseMissing('comments', ['item_id' => $item->id, 'comment' => 'テストコメントです。']);
    }

    //コメント送信バリデーションエラーテスト（必須）
    public function test_send_comment_validation_error_required()
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

        //商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        //コメント送信
        $response = $this->post("/item/{$item->id}/comment", ['comment' => '']);

        //リダイレクトされたことを確認
        $response->assertStatus(302);

        //エラーが含まれているか
        $response->assertSessionHasErrors(['comment']);

        //画面にメッセージが表示されているか
        $response = $this->followRedirects($response);
        $response->assertSee('商品コメントを入力してください');
    }

    //コメント送信バリデーションエラーテスト（最大文字数）
    public function test_send_comment_validation_error_max()
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

        //商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        //コメント送信
        $longComment = str_repeat('あ', 256);
        $response = $this->post("/item/{$item->id}/comment", ['comment' => $longComment]);

        //リダイレクトされたことを確認
        $response->assertStatus(302);

        //エラーが含まれているか
        $response->assertSessionHasErrors(['comment']);

        //画面にメッセージが表示されているか
        $response = $this->followRedirects($response);
        $response->assertSee('商品コメントは255文字以内で入力してください');
    }
}
