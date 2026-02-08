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
        <form action="/purchase/{{ $item->id }}" method="post">
            @csrf
            <div class="purchase-content">
                <div class="purchase-content_left">
                    <div class="box">
                        <div class="item_detail">
                            <img src="{{ asset('storage/' . $item->item_path) }}" alt="{{ $item->item_name }}" />
                            <div class="item_detail_right">
                                <h1>{{ $item->item_name }}</h1>
                                <p class="price"><span>￥</span>{{ number_format($item->price) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <h2>支払い方法</h2>
                        <div class="payment">
                            <select id="pay_select" name="payment_method">
                                <option value="">選択してください</option>
                                <option value="1" {{ (old('payment_method') == 1) ? 'selected' : '' }}>コンビニ払い</option>
                                <option value="2" {{ (old('payment_method') == 2) ? 'selected' : '' }}>カード支払い</option>
                            </select>
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
                            〒<input type="text" name="shipping[post_code]" value="{{ $profile->post_code }}" readonly>
                            <div class="shipping_address">
                                <input type="text" name="shipping[address]" value="{{ $profile-> address}}" size="{{ mb_strlen($profile->address) }}" readonly>
                                <input type="text" name="shipping[building]" value="{{ $profile->building }}" size="{{ mb_strlen($profile->building) }}" readonly>
                            </div>
                        </div>
                        @error('shipping')
                        <ul class="form-error">
                            <li>{{ $message }}</li>
                        </ul>
                        @enderror
                    </div>
                </div>
                <div class="purchase-content_right">
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
                                <p id="payment_method" class="payment_method"></p>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="item_name" value="{{ $item->item_name }}">
                    <input type="hidden" name="price" value="{{ $item->price }}">
                    <button class="button button_submit" type="submit">購入する</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.getElementById('pay_select').addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        const output = document.getElementById('payment_method');

        if (this.value === '') {
            output.textContent = '';
        } else {
            output.textContent = selectedText;
        }
    });
</script>
@endsection