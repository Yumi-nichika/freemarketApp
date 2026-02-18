<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserProfile;

/**
 * ユーザー情報変更機能
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_profile()
    {
        //ユーザー作成
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test1@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user_profile = UserProfile::factory()->create(['user_id' => $user->id]);

        //ログイン
        $response = $this->post('/login', [
            'email' => 'test1@example.com',
            'password' => 'password123',
        ]);

        //認証確認
        $this->assertAuthenticatedAs($user);

        //プロフィール編集画面にアクセス
        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertSee($user_profile->icon_path);
        $response->assertSee($user->name);
        $response->assertSee($user_profile->post_code);
        $response->assertSee($user_profile->address);
        $response->assertSee($user_profile->bulilding);
    }
}
