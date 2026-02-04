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
                        <a href="/item/{{ $item->id }}" class="no-link">
                            <div class="item_img">
                                <img src="{{ asset('storage/' . $item->item_path) }}" alt="{{ $item->item_name }}" />
                            </div>
                            <div class="item_name">
                                <p>{{ $item->item_name }}</p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @auth
        <div class="list mylist">
            <div class="list-content">
                <div class="grid">
                    @foreach($likes as $like)
                    <div class="item">
                        <a href="/item/{{ $like->item->id }}" class="no-link">
                            <div class="item_img">
                                <img src="{{ asset('storage/' . $like->item->item_path) }}" alt="{{ $like->item->item_name }}" />
                            </div>
                            <div class="item_name">
                                <p>{{ $like->item->item_name }}</p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endauth
    </div>

</div>
@endsection