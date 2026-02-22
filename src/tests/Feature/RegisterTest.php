<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

/**
 * 会員登録機能テスト
 * メール認証機能テスト
 */
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * バリデーションエラーの全パターンをテスト
     * @dataProvider invalidRegisterProvider
     */
    public function test_register_validation_errors($data, $errorField, $expectedMessage)
    {
        $response = $this->post('/register', $data);

        //エラーメッセージ確認
        $response->assertSessionHasErrors([$errorField => $expectedMessage]);

        //データベース未登録確認
        $this->assertCount(0, User::all());
    }

    /**
     * バリデーションエラーテスト
     */
    public function invalidRegisterProvider()
    {
        $valid = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        return [
            '名前が未入力' => [array_merge($valid, ['name' => '']), 'name', 'お名前を入力してください'],
            'メールが未入力' => [array_merge($valid, ['email' => '']), 'email', 'メールアドレスを入力してください'],
            'パスワードが未入力' => [array_merge($valid, ['password' => '', 'password_confirmation' => '']), 'password', 'パスワードを入力してください'],
            'パスワードが7文字以下' => [array_merge($valid, ['password' => '1234567', 'password_confirmation' => '1234567']), 'password', 'パスワードは8文字以上で入力してください'],
            'パスワード不一致' => [array_merge($valid, ['password_confirmation' => 'different']), 'password', 'パスワードと一致しません'],
        ];
    }

    /**
     * 会員登録、認証メール送信テスト
     */
    public function test_register_success()
    {
        Mail::fake();

        //ユーザー作成
        $data = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->post('/register', $data);

        //メール認証誘導画面遷移確認
        $response->assertRedirect('/email-sent');

        //データベース登録確認
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        //メール認証誘導画面に遷移
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user)->get('/email-sent');

        //メール送信確認
        Mail::assertSent(VerificationCodeMail::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    /**
     * メール認証誘導画面で「認証はこちらから」ボタン押下テスト
     */
    public function test_verify_code_button_click()
    {
        Mail::fake();

        //ユーザー作成
        $data = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->post('/register', $data);

        //画面遷移、登録確認
        $response->assertRedirect('/email-sent');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $user = User::where('email', 'test@example.com')->first();

        //メール認証画面遷移確認
        $this->withMiddleware();
        $response = $this->actingAs($user)->get('/verify-code');
        $response->assertStatus(200);
    }

    /**
     * メール認証テスト
     */
    public function test_email_verification()
    {
        Mail::fake();

        //ユーザー作成
        $data = [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->post('/register', $data);

        //画面遷移、登録確認
        $response->assertRedirect('/email-sent');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        //認証コード取得
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user)->get('/email-sent');
        $verificationCode = VerificationCode::where('user_id', $user->id)->first();

        //メール認証画面へ遷移し、コードを入力して認証
        $verifyResponse = $this->actingAs($user)->post('/verify-code', ['code' => $verificationCode->code,]);

        //プロフィール画面遷移確認
        $verifyResponse->assertRedirect('/mypage/profile');

        //認証確認
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
