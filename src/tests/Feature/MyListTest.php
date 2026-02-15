<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use App\Models\Like;

/**
 * マイリスト一覧機能
 */
class MyListTest extends TestCase
{
    use RefreshDatabase;

    //購入済み商品テスト
    public function test_show_mylist_item()
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

        //商品一覧ページにアクセス
        $response = $this->get('/?tab=mylist');

        //検証
        $response->assertStatus(200);

        // 画面内に商品名が含まれているか
        $response->assertSee('テスト用商品A');

        // ビューに渡された変数の件数確認
        $response->assertViewHas('likes', function ($likes) {
            return $likes->count() === 1;
        });
    }

    //購入済み商品テスト
    public function test_show_mylist_sold_item()
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

        //売却済み商品作成
        $sold_item = SoldItem::factory()->create(['item_id' => $item1->id, 'user_id' => $user2->id]);

        //いいね作成
        $like1 = Like::factory()->create(['item_id' => $item1->id, 'user_id' => $user2->id]);
        $like2 = Like::factory()->create(['item_id' => $item2->id, 'user_id' => $user2->id]);

        //user2でログイン
        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user2);

        //商品一覧ページにアクセス
        $response = $this->get('/?tab=mylist');

        //検証
        $response->assertStatus(200);

        //マイリスト全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list mylist">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $mylistArea)) {
            $mylistHtml = $mylistArea[0];

            //商品Aを抽出
            if (preg_match('/テスト用商品A.*?<\/a>/s', $mylistHtml, $itemABlock)) {
                $itemAHtml = $itemABlock[0];

                //検証
                $this->assertStringContainsString('class="sold"', $itemAHtml, '商品Aのブロック内にsoldラベルが見つかりません');
            } else {
                $this->fail('マイリスト内に商品Aのリンク(aタグ)が見つかりませんでした');
            }
        }
    }

    //未認証テスト
    public function test_show_mylist_item_when_not_authenticated()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        //マイリストのコンテナ要素非表示確認
        $response->assertDontSee('<div class="list mylist">', false);
    }
}
