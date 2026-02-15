<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

/**
 * 商品検索機能
 */
class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    //検索テスト
    public function test_search_items()
    {
        //テストデータ作成
        $item1 = Item::factory()->create(['item_name' => '腕時計']);
        $item2 = Item::factory()->create(['item_name' => 'HDD']);

        //検索
        $response = $this->get('/search?tab=&free=腕時計');

        //検証
        $response->assertStatus(200);

        //商品一覧全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list recommend">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $listArea)) {
            $listHtml = $listArea[0];

            //検索した商品表示
            $this->assertStringContainsString($item1->item_name, $listHtml);

            //検索していない商品非表示
            $this->assertStringNotContainsString($item2->item_name, $listHtml);
        }
    }

    //マイリスト検索状態保持テスト
    public function test_search_items_save_mylist()
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
        $item1 = Item::factory()->create(['user_id' => $user1->id, 'item_name' => 'テスト用商品A']);
        $item2 = Item::factory()->create(['user_id' => $user1->id, 'item_name' => 'テスト用商品B']);

        //いいね作成
        $like = Like::factory()->create(['item_id' => $item1->id, 'user_id' => $user2->id]);

        //user2でログイン
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user2);

        //検索
        $searchWord = '腕時計';
        $response = $this->get("/search?tab=&free={$searchWord}");

        //検証
        $response->assertStatus(200);

        //タブ切り替え
        $content = $response->getContent();
        if (preg_match('/<a href="([^"]+)"[^>]*>マイリスト<\/a>/', $content, $matches)) {
            // BladeのfullUrlWithQueryが作ったURL
            $generatedUrl = $matches[1];

            //URLに検索ワードが含まれているか
            $this->assertStringContainsString('free=' . urlencode($searchWord), $generatedUrl);

            //抽出したURLを使って検索
            $nextResponse = $this->get($generatedUrl);

            //遷移先でも検索ワードが維持されているか
            $nextResponse->assertStatus(200);
            $nextResponse->assertSee('value="' . $searchWord . '"', false);
        } else {
            $this->fail('マイリストへのリンク（URL）がHTML内に見つかりませんでした。');
        }
    }
}
