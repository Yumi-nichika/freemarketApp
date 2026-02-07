<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\UserProfile;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面表示
     */
    public function index($item_id)
    {
        $item = Item::find($item_id);

        $profile = UserProfile::find(auth()->id());

        return view('purchase', compact('item', 'profile'));
    }

    /**
     * 購入
     */
    public function store() {}

    /**
     * 送付先住所変更画面表示
     */
    public function edit($item_id)
    {
        $user_profile = UserProfile::find(auth()->id());

        return view('address', compact('item_id', 'user_profile'));
    }

    /**
     * 住所更新
     */
    public function update(AddressRequest $request, $item_id)
    {
        $user_profile = UserProfile::find(auth()->id());

        $data = $request->only([
            'post_code',
            'address',
            'building',
        ]);

        $user_profile->fill($data);
        $user_profile->save();

        return redirect('/purchase/' . $item_id);
    }
}
