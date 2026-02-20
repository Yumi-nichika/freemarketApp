@extends('layouts.common')

@section('show-center', 'true')
@section('show-right', 'true')

@section('title')
商品購入画面
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('main')
<div class="content">
    <div class="content-form">
        <div class="purchase-content">
            <div class="purchase-content_left">
                <div class="box">
                    <div class="item_detail">
                        <div class="item_detail_left">
                            <img src="{{ asset('storage/' . $item->item_path) }}" alt="{{ $item->item_name }}" />
                        </div>
                        <div class="item_detail_right">
                            <h1>{{ $item->item_name }}</h1>
                            <p class="price"><span>￥</span>{{ number_format($item->price) }}</p>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <h2>支払い方法</h2>
                    <div class="payment">
                        <form action="/purchase/{{ $item->id }}" method="GET" id="payment_form">
                            <select id="pay_select" name="select_method" onchange="this.form.submit()">
                                <option value="">選択してください</option>
                                <option value="1" {{ $selectedMethod == 1 ? 'selected' : '' }}>コンビニ支払い</option>
                                <option value="2" {{ $selectedMethod == 2 ? 'selected' : '' }}>カード支払い</option>
                            </select>
                        </form>
                    </div>
                    @error('payment_method')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
                <div class="box">
                    <div class="shipping_title">
                        <h2>配送先</h2>
                        <a href="/purchase/address/{{ $item->id }}" class="link">変更する</a>
                    </div>
                    <div class="shipping">
                        <p>〒{{ $profile?->post_code }}</p>
                        <p>{{ $profile?->address }}　{{ $profile?->building }}</p>
                    </div>
                    @error('shipping')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
            </div>
            <div class="purchase-content_right">
                <form action="/purchase/{{ $item->id }}" method="post">
                    @csrf
                    <table class="sub_total_table">
                        <tr>
                            <td class="sub_total_table_header">
                                商品代金
                            </td>
                            <td class="sub_total_table_detail">
                                <p class="price"><span>￥</span>{{ number_format($item->price) }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="sub_total_table_header">
                                支払い方法
                            </td>
                            <td class="sub_total_table_detail">
                                <p id="payment_method" class="payment_method">
                                    {{ match(request('select_method')) { '1' => 'コンビニ支払い', '2' => 'カード支払い', default => '' } }}
                                </p>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="item_name" value="{{ $item->item_name }}">
                    <input type="hidden" name="price" value="{{ $item->price }}">
                    <input type="hidden" name="payment_method" value="{{ $selectedMethod }}">
                    <input type="hidden" name="shipping[post_code]" value="{{ $profile?->post_code }}">
                    <input type="hidden" name="shipping[address]" value="{{ $profile?->address }}">
                    <input type="hidden" name="shipping[building]" value="{{ $profile?->building }}">
                    <button class="button button_submit" type="submit">購入する</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection