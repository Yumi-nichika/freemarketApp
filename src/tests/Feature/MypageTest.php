<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Item;
use App\Models\SoldItem;

/**
 * ユーザー情報取得機能
 */
class MypageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 特定のSeederのみを実行
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    }

    public function test_show_mypage()
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
        $item1 = Item::factory()->create(['user_id' => $user1->id, 'item_name' => 'テスト用商品A']);
        $item2 = Item::factory()->create(['user_id' => $user2->id, 'item_name' => 'テスト用商品B']);

        //購入済み商品作成
        $sold_item = SoldItem::factory()->create(['item_id' => $item1->id, 'user_id' => $user2->id]);

        //user2でログイン
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user2);

        //マイページにアクセス
        $response = $this->get('/mypage');
        $response->assertStatus(200);

        $response->assertSee($user_profile->icon_path);
        $response->assertSee($user2->name);

        //出品した商品全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list sell">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $selllistArea)) {
            $selllistHtml = $selllistArea[0];

            //出品した商品表示
            $this->assertStringContainsString($item2->item_name, $selllistHtml);

            //出品してない商品非表示
            $this->assertStringNotContainsString($item1->item_name, $selllistHtml);
        }

        //購入した商品全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list buy">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $buylistArea)) {
            $buylistHtml = $buylistArea[0];

            //購入した商品表示
            $this->assertStringContainsString($item1->item_name, $buylistHtml);

            //購入してない商品非表示
            $this->assertStringNotContainsString($item2->item_name, $buylistHtml);
        }
    }
}
