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
                        <select id="pay_select">
                            <option value="">選択してください</option>
                            @foreach($pays as $pay)
                            <option value="{{ $pay->id}}">{{ $pay->payment_method }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box">
                    <div class="address_title">
                        <h2>配送先</h2>
                        <a href="/purchase/address/{{ $item->id }}" class="link">変更する</a>
                    </div>
                    <div class="address">
                        <p>〒{{ $profile->post_code }}</p>
                        <p>{{ $profile-> address}}　{{ $profile->building }}</p>
                    </div>
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
                <a href="" class="button button_submit">購入する</a>
            </div>
        </div>
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