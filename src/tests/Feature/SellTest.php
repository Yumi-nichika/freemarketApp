<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * 出品商品情報登録機能
 */
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
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        //ログイン
        $this->actingAs($user);

        //出品画面にアクセス
        $response = $this->get('/sell');
        $response->assertStatus(200);

        //商品画像
        Storage::fake('public');
        $file = UploadedFile::fake()->image('item.jpg');
        $tmpPath = $file->store('tmp', 'public');

        //カテゴリをDBから取得
        $categoryId = \App\Models\Category::first()->id;

        //出品
        $response = $this->withSession([
            'tmp_item_image_path' => $tmpPath,
        ])->post('/sell', [
            'item_image' => $file,
            'categories' => [$categoryId],
            'condition_id' => 2,
            'item_name' => 'テスト用商品A',
            'brand_name' => 'テストブランド',
            'detail' => '商品の説明です。',
            'price' => 500,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        //登録確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'item_name' => 'テスト用商品A',
            'brand_name' => 'テストブランド',
            'price' => 500,
            'condition_id' => 2,
            'detail' => '商品の説明です。',
            'item_path' => 'items/' . basename($tmpPath),
        ]);

        $item = \App\Models\Item::first();

        $this->assertDatabaseHas('category_item', [
            'item_id' => $item->id,
            'category_id' => $categoryId,
        ]);

        //画像保存確認
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists('items/' . basename($tmpPath));
    }
}
