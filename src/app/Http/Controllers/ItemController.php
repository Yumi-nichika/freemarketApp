<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * 商品一覧画面（トップ画面）表示
     */
    public function index()
    {
        $query = Item::query();

        //ログインしている場合、自分が出品した商品を除く
        if (Auth::check()) {
            $query->where('seller_user_id', '!=', Auth::id());
        }

        $items = $query->get();

        return view('index', compact('items'));
    }

    /**
     * 商品詳細画表示
     */
    public function show($item_id)
    {
        $item = Item::with('condition', 'categories')->find($item_id);
        return view('item', compact('item'));
    }
}
