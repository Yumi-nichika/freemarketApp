<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserProfile;

class MypageController extends Controller
{
    /**
     * プロフィール画面表示
     */
    public function index()
    {
        $user = Auth::user();

        $user_id = auth()->id();
        $user_profile = UserProfile::find($user_id);

        return view('mypage', compact('user', 'user_profile'));
    }

    /**
     * プロフィール編集画面（設定画面）表示
     */
    public function edit()
    {
        $user = Auth::user();

        $user_id = auth()->id();

        $user_profile = UserProfile::firstOrNew([
            'user_id' => $user->id,
        ]);

        return view('profile', compact('user', 'user_profile'));
    }


    /**
     * プロフィール更新（初回作成＋更新共通）
     */
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        //ユーザー名更新
        $user->update($request->only(['name']));

        //プロフィール作成・更新
        $user_profile = UserProfile::firstOrNew([
            'user_id' => $user->id,
        ]);

        //プロフィールが存在していなかったら「true」→新規作成
        $isNew = ! $user_profile->exists;

        $data_profile = $request->only([
            'post_code',
            'address',
            'building',
        ]);

        //旧アイコン退避
        $oldIconPath = $user_profile->icon_path;

        //アイコンがあったら保存
        if (session()->has('tmp_icon_path')) {
            $tmpPath = session('tmp_icon_path');

            $newPath = str_replace('tmp/', 'icons/', $tmpPath);
            Storage::disk('public')->move($tmpPath, $newPath);

            $data_profile['icon_path'] = $newPath;

            session()->forget('tmp_icon_path');
        }

        //プロフィール保存
        $user_profile->fill($data_profile);
        $user_profile->save();

        //旧アイコン削除
        if (
            isset($data_profile['icon_path']) &&
            $oldIconPath &&
            Storage::disk('public')->exists($oldIconPath)
        ) {
            Storage::disk('public')->delete($oldIconPath);
        }

        // ---------- リダイレクト分岐 ----------
        return $isNew
            ? redirect('/')
            : redirect('/mypage');
    }
}
