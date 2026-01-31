<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MypageController extends Controller
{
    /**
     * プロフィール画面表示
     */
    public function index()
    {
        return view('mypage');
    }

    /**
     * プロフィール編集画面（設定画面）表示
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * プロフィール更新
     */
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        $data = $request->only([
            'name',
            'post_code',
            'address',
            'building',
        ]);

        // tmp が存在する場合のみ処理
        if (session()->has('tmp_icon_path')) {

            $tmpPath = session('tmp_icon_path');

            // 旧アイコンを退避
            $oldIconPath = $user->icon_path;

            // tmp → icons に移動
            $newPath = str_replace('tmp/', 'icons/', $tmpPath);

            Storage::disk('public')->move($tmpPath, $newPath);

            // DB保存用
            $data['icon_path'] = $newPath;

            // 旧アイコン削除（存在＆デフォルト以外）
            if ($oldIconPath && Storage::disk('public')->exists($oldIconPath)) {
                Storage::disk('public')->delete($oldIconPath);
            }

            // session クリア
            session()->forget('tmp_icon_path');
        }

        $user->update($data);

        return view('mypage');
    }
}
