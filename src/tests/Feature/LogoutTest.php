<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

/**
 * ログアウト機能テスト
 */
class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログアウトテスト
     */
    public function test_logout_success()
    {
        //テストユーザーを作成してログイン状態にする
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        //ログアウト
        $response = $this->actingAs($user)->post('/logout');

        //認証状態確認
        $this->assertGuest();
    }
}
