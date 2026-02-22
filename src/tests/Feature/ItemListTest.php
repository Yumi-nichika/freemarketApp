<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;

/**
 * 商品一覧機能
 */
class ItemListTest extends TestCase
{
    use RefreshDatabase;

    //全商品取得テスト
    public function test_show_items()
    {
        //テストデータ作成
        $items = Item::factory()->count(10)->create();

        //商品一覧ページにアクセス
        $response = $this->get('/');
        $response->assertStatus(200);

        // 画面内に商品名が含まれているか
        foreach ($items as $item) {
            $response->assertSee($item->item_name);
        }

        // ビューに渡された変数の件数確認
        $response->assertViewHas('items', function ($items) {
            return $items->count() === 10;
        });
    }

    //購入済み商品テスト
    public function test_show_sold_item()
    {
        //出品ユーザー作成
        $sell_user = User::factory()->create();

        //出品商品作成
        $item1 = Item::factory()->create(['user_id' => $sell_user->id, 'item_name' => 'テスト用商品A']);
        $item2 = Item::factory()->create(['user_id' => $sell_user->id, 'item_name' => 'テスト用商品B']);

        //購入ユーザー作成
        $purchase_user = User::factory()->create();

        //売却済み商品作成
        $sold_item = SoldItem::factory()->create(['item_id' => $item1->id, 'user_id' => $purchase_user->id]);

        //商品一覧ページにアクセス
        $response = $this->get('/');
        $response->assertStatus(200);

        //商品一覧全体を抽出
        $content = $response->getContent();
        if (preg_match('/<div class="list recommend">.*?<\/div>\s*<\/div>\s*<\/div>/s', $content, $listArea)) {
            $listHtml = $listArea[0];

            //売却済み商品を抽出
            if (preg_match('/テスト用商品A.*?<\/a>/s', $listHtml, $itemABlock)) {
                $itemAHtml = $itemABlock[0];

                //検証
                $this->assertStringContainsString('class="sold"', $itemAHtml, '商品Aのブロック内にsoldラベルが見つかりません');
            } else {
                $this->fail('リスト内に商品Aのリンク(aタグ)が見つかりませんでした');
            }
        }
    }

    //自分が出品した商品は非表示テスト
    public function test_show_item_login()
    {
        //出品ユーザー作成
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
        $item1 = Item::factory()->count(3)->create(['user_id' => $user1->id]);
        $item2 = Item::factory()->count(2)->create(['user_id' => $user2->id]);


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


        //他人の商品（user1の商品）が表示されていることを確認
        foreach ($item1 as $item) {
            $response->assertSee($item->item_name);
        }

        //自分の商品（user2の商品）が表示されていないことを確認
        foreach ($item2 as $item) {
            $response->assertDontSeeText($item->item_name);
        }
    }
}
