<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Item;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;

/**
 * 商品詳細情報機能
 */
class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 特定のSeederのみを実行
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
    }

    //商品詳細表示テスト
    public function test_show_item_detail()
    {
        //出品商品作成
        $categories = Category::all();
        $selectedCategory = $categories->random();
        $item = Item::factory()->hasAttached($selectedCategory)->create();

        //設定されたコンディション
        $expectedConditionName = config('condition.' . $item->condition_id);

        //ユーザー作成
        $user = User::factory()->create();
        $user_profile = UserProfile::factory()->create(['user_id' => $user->id]);

        //いいね
        $like = Like::factory()->create(['item_id' => $item->id, 'user_id' => $user->id]);

        //コメント
        $comment = Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        //商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee($item->item_name);

        $response->assertSee($item->brand_name);

        $formattedPrice = number_format($item->price);
        $response->assertSeeInOrder(['￥', $formattedPrice, '（税込）']);

        $response->assertSeeInOrder(['img/hart_off.png', '1']);

        $response->assertSeeInOrder(['img/fukidashi.png', '1']);

        $response->assertSee(['商品説明', $item->detail]);

        $response->assertSee(['カテゴリー', $selectedCategory->category]);

        $response->assertSeeInOrder(['商品の状態', $expectedConditionName]);

        $response->assertSee('コメント(1)');

        $response->assertSee($user->name);

        $response->assertSee($comment->comment);
    }

    //複数カテゴリ表示テスト
    public function test_show_item_detail_categories()
    {
        //出品商品作成
        $categories = Category::all();
        $selectedCategories = $categories->random(3);
        $item = Item::factory()->hasAttached($selectedCategories)->create();

        //商品詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        foreach ($selectedCategories as $category) {
            $response->assertSee($category->category);
        }

        $this->assertGreaterThanOrEqual(3, substr_count($response->getContent(), 'class="category"'));
    }
}
