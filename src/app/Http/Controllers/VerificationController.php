<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:4'
        ]);

        $user = Auth::user();

        $record = VerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['code' => '認証コードが正しくないか、入力期限が切れています。']);
        }

        //認証日時記録
        User::where('id', $user->id)->update([
            'email_verified_at' => now()
        ]);


        $record->delete();

        return redirect('/mypage/profile');
    }

    public function resend()
    {
        $user = Auth::user();

        // 既存コード削除
        VerificationCode::where('user_id', $user->id)->delete();

        // 新しいコード生成
        $code = rand(1000, 9999);

        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        // メール送信
        Mail::to($user->email)->send(new VerificationCodeMail($code));

        return back()->with('message', '認証メールを再送しました');
    }
}
