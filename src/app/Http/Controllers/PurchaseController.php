<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\UserProfile;
use App\Models\SoldItem;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面表示
     */
    public function show($item_id)
    {
        $item = Item::find($item_id);

        $profile = UserProfile::find(auth()->id());

        return view('purchase', compact('item', 'profile'));
    }

    /**
     * 購入
     */
    public function store(PurchaseRequest $request, $item_id)
    {
        $payment_method = $request->payment_method;
        $data['payment_method'] = $payment_method;
        $data['post_code'] = $request->input('shipping.post_code');
        $data['address']   = $request->input('shipping.address');
        $data['building']  = $request->input('shipping.building');

        $data['item_id'] = $item_id;
        $data['user_id'] = auth()->id();

        SoldItem::create($data);

        $item_name = $request->item_name;
        $price = $request->price;

        //stripeで決済
        Stripe::setApiKey(config('services.stripe.secret'));

        //コンビニ払い
        if ($payment_method == 1) {
            $session = Session::create([
                'payment_method_types' => ['konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item_name,
                        ],
                        'unit_amount' => $price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'payment_method_options' => [
                    'konbini' => [
                        'expires_after_days' => 3,
                    ],
                ],

                // 成功したらマイページ
                'success_url' => url('/mypage?page=buy'),
                // キャンセル・失敗時は購入画面に戻す
                'cancel_url' => url("/purchase/{$item_id}"),
            ]);
        }

        //カード支払い
        else if ($payment_method == 2) {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item_name,
                        ],
                        'unit_amount' => $price, // 1000円
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',

                // 成功したらマイページ
                'success_url' => url('/mypage?page=buy'),
                // キャンセル・失敗時は購入画面に戻す
                'cancel_url' => url("/purchase/{$item_id}"),
            ]);
        }
        return redirect($session->url);
    }

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
