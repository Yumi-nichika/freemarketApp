@extends('layouts.common')

@section('show-center', 'true')
@section('show-right', 'true')

@section('title')
商品詳細画面
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('main')
<div class="content">
    <div class="content-form">
        <div class="item-content">
            <div class="item_img">
                <img src="{{ asset('storage/' . $item->item_path) }}" alt="{{ $item->item_name }}" />
            </div>
            <div class="item_details">
                <h1>{{ $item->item_name }}</h1>
                <p>{{ $item->brand_name }}</p>
                <p class="item_details_price"><span>￥</span>{{ number_format($item->price) }}<span>（税込）</span></p>
                <div class="reaction_icons">
                    <div class="icon">
                        <form action="/item/{{ $item->id }}/mylist" method="post">
                            @csrf
                            <button type="submit"><img src="{{ asset('img/hart_off.png') }}"></button>
                        </form>
                        <p class="tac">0</p>
                    </div>
                    <div class="icon">
                        <img src="{{ asset('img/fukidashi.png') }}">
                        <p class="tac">0</p>
                    </div>
                </div>
                <a href="/purchase/{{ $item->id }}" class="button button_submit">購入手続きへ</a>

                <div class="mt50">
                    <h2>商品説明</h2>
                    <p class="item_details_detail">{{ $item->detail }}</p>
                </div>

                <div class="mt50">
                    <h2>商品の情報</h2>
                    <table class="item_details_table">
                        <tr class="table_row">
                            <th class="table_header">カテゴリー</th>
                            <td class="table_text">
                                @foreach($item->categories as $category)
                                <span class="category">
                                    {{ $category->category }}
                                </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr class="table_row">
                            <th class="table_header">商品の状態</th>
                            <td class="table_text">
                                {{ $item->condition->condition }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="mt50">
                    <h3>商品へのコメント</h3>
                    <form action="/item/{{ $item->id }}/comment" method="post">
                        @csrf
                        <textarea name="comment">{{ old('comment', request('comment')) }}</textarea>
                        @error('password')
                        <ul class="form-error">
                            <li>{{ $message }}</li>
                        </ul>
                        @enderror
                        <button class="button button_submit mt20" type="submit">コメントを送信する</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection