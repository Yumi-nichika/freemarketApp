<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

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
        //商品情報
        $item = Item::with('condition', 'categories')->find($item_id);

        //この商品の全体のいいね数
        $likes = Like::where('item_id', $item_id)->get();
        $likes_count = $likes->count();

        //この商品をユーザーがいいねしているか
        //ログインしていない場合は0を返す
        $like_count = 0;
        if (Auth::check()) {
            $like = Like::where('item_id', $item_id)->where('user_id', auth()->id())->get();
            $like_count = $like->count();
        }

        //コメント
        $comments = Comment::with('user', 'profile')->where('item_id', $item_id)->get();

        return view('item', compact('item', 'comments', 'likes_count', 'like_count'));
    }

    /**
     * いいね（マイリスト登録・解除）
     */
    public function like($item_id)
    {
        $like = Like::where('item_id', $item_id)->where('user_id', auth()->id())->first();

        //登録あり→解除
        if ($like) {
            Like::where('item_id', $item_id)
                ->where('user_id', auth()->id())
                ->delete();
        }

        //登録なし→登録
        else {
            Like::create([
                'item_id' => $item_id,
                'user_id' => auth()->id(),
            ]);
        }

        return redirect('/item/' . $item_id);
    }


    /**
     * コメント投稿
     */
    public function comment(CommentRequest $request, $item_id)
    {
        $data = $request->only([
            'comment',
        ]);

        $data['item_id'] = $item_id;
        $data['user_id'] = auth()->id();

        Comment::create($data);

        return redirect('/item/' . $item_id);
    }
}
