@extends('layouts.common')

@section('show-center', 'true')
@section('show-right', 'true')

@section('title')
商品一覧画面（トップ画面）
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('main')
<div class="listToggle">
    <!-- 切り替え用 -->
    <input type="radio" name="tab" id="tab-recommend"
        {{ auth()->check() ? '' : 'checked' }}>

    <input type="radio" name="tab" id="tab-mylist"
        {{ auth()->check() ? 'checked' : '' }}>

    <div class="listToggle__buttons">
        <label for="tab-recommend">おすすめ</label>
        <label for="tab-mylist">マイリスト</label>
    </div>

    <div class="listToggle__line"></div>

    <!-- 一覧 -->
    <div class="listToggle__contents">
        <div class="list recommend">
            <div class="list-content">
                <div class="grid">
                    @foreach($items as $item)
                    <div class="item">
                        <div class="item__img">
                            <img src="{{ asset('storage/' . $item->item_path) }}" alt="" />
                        </div>
                        <div class="item__name">
                            <p>{{ $item->item_name }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="list mylist {{ auth()->check() ? '' : 'mylist_is-hidden' }}">
            いいね一覧の内容
        </div>
    </div>

</div>
@endsection