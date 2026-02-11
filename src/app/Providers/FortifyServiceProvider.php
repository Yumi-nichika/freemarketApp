<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //「/register」で登録画面を開く
        Fortify::registerView(function () {
            return view('auth.register');
        });

        //登録画面処理（バリデーション・DB登録）
        Fortify::createUsersUsing(CreateNewUser::class);

        //登録後、認証画面へ
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/email-sent');
            }
        });


        //「/login」でログイン画面を開く
        Fortify::loginView(function () {
            return view('auth.login');
        });

        //ログイン時のバリデーションをカスタム
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

        //連続ログインブロック
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
