@extends('layouts.common')

@section('show-center', 'true')
@section('show-right', 'true')

@section('title')
プロフィール画面
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('main')
<div class="content">
    <div class="content-form">
        <div class="profile__inner">
            <div class="profile__left">
                <div class="icon-preview">
                    <img id="iconPreview"
                        src="{{ $user_profile->icon_path ? asset('storage/' . $user_profile->icon_path) : asset('img/icon_default.png') }}"
                        alt="icon">
                </div>

                <h2>{{ $user->name }}</h2>
            </div>

            <div class="profile__right">
                <a class="button button_red_square w30" href="/mypage/profile">プロフィールを編集</a>
            </div>
        </div>
    </div>
</div>

<div class="listToggle">
    <!-- 切り替え用 -->
    <input type="radio" name="tab" id="tab-sell"
        {{ request('page') !== 'buy' ? 'checked' : '' }}>

    <input type="radio" name="tab" id="tab-buy"
        {{ request('page') === 'buy' ? 'checked' : '' }}>

    <div class="listToggle__buttons">
        <label for="tab-sell">
            <a href="/mypage?page=sell" class="tab-link">出品した商品</a>
        </label>
        <label for="tab-buy">
            <a href="/mypage?page=buy" class="tab-link">購入した商品</a>
        </label>
    </div>

    <div class="listToggle__line"></div>

    <!-- 一覧 -->
    <div class="listToggle__contents">
        <div class="list sell">
            <div class="list-content">
                <div class="grid">
                    @foreach($items as $item)
                    <div class="item">
                        <div class="item_img">
                            <img src="{{ asset('storage/' . $item->item_path) }}" alt="{{ $item->item_name }}" />
                        </div>
                        <div class="item_name">
                            <p>{{ $item->item_name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @auth
        <div class="list buy">
            <div class="list-content">
                <div class="grid">
                    @foreach($sold_items as $sold_item)
                    <div class="item">
                        <div class="item_img">
                            <img src="{{ asset('storage/' . $sold_item->item->item_path) }}" alt="{{ $sold_item->item->item_name }}" />
                        </div>
                        <div class="item_name">
                            <p>{{ $sold_item->item->item_name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endauth
    </div>

</div>
@endsection