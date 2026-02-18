<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 特定のSeederのみを実行
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    }

    public function test_add_sell_item()
    {
        //ユーザー作成
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);

        //ログイン
        $response = $this->post('/login', [
            'email' => 'test1@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user);

        //出品画面にアクセス
        $response = $this->get('/sell');
        $response->assertStatus(200);

        //ダミー商品画像
        Storage::fake('public');
        $file = UploadedFile::fake()->create('item.jpg', 100, 'image/jpeg');

        //出品
        $formData = [
            'item_image' => $file,
            'categories' => [1],
            'condition_id' => 2,
            'item_name' => 'テスト用商品A',
            'brand_name' => 'テストブランド',
            'detail' => '商品の説明です。',
            'price' => 500,
        ];
        $response = $this->post("/sell", $formData);

        //登録確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'item_name' => 'テスト用商品A',
            'brand_name' => 'テストブランド',
            'price' => 500,
            'condition_id' => 2,
            'detail' => '商品の説明です。',
            'item_path' => 'items/' . $file->hashName(),
        ]);

        $savedItem = \App\Models\Item::where('user_id', $user->id)->first();

        $this->assertDatabaseHas('category_item', [
            'item_id' => $savedItem->id,
            'category_id' => 1,
        ]);

        //画像保存確認
        $this->assertTrue(
            Storage::disk('public')->exists('items/' . $file->hashName()),
            "File was not saved."
        );
    }
}
