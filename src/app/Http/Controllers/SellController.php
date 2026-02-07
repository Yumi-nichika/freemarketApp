<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Category;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Storage;

class SellController extends Controller
{
    /**
     * 商品出品画面表示
     */
    public function index()
    {
        $conditions = Condition::all();
        $categries = Category::all();

        return view('sell', compact('conditions', 'categries'));
    }

    /**
     * 商品登録（出品）
     */
    public function store(ExhibitionRequest $request)
    {
        //itemsテーブルに登録するデータ
        $data = $request->only([
            'condition_id',
            'item_name',
            'brand_name',
            'detail',
            'price'
        ]);

        //出品ユーザー追加
        $data['seller_user_id'] = auth()->id();

        //商品画像のパス追加
        if (session()->has('tmp_item_image_path')) {
            $tmpPath = session('tmp_item_image_path');

            $newPath = str_replace('tmp/', 'items/', $tmpPath);
            Storage::disk('public')->move($tmpPath, $newPath);

            $data['item_path'] = $newPath;

            session()->forget('tmp_item_image_path');
        }

        //itemsテーブルに保存、id取得
        $item = Item::create($data);

        //カテゴリー
        $categoryIds = $request->categories;

        //カテゴリー登録
        $item->categories()->sync($categoryIds);

        return redirect('/mypage');
    }
}
