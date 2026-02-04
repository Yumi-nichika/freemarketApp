<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\UserProfile;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面表示
     */
    public function index($item_id)
    {
        $item = Item::find($item_id);

        $pays = PaymentMethod::all();

        $profile = UserProfile::find(auth()->id());

        return view('purchase', compact('item', 'pays', 'profile'));
    }
}
