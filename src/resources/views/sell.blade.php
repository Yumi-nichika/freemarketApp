@extends('layouts.common')

@section('show-center', 'true')
@section('show-right', 'true')

@section('title')
商品出品画面
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('main')
<div class="content">
    <h1 class="content-title">
        商品の出品
    </h1>
    <div class="sell-form">
        <form class="form" action="/sell" method="post" enctype="multipart/form-data">
            @csrf
            <h3>商品画像</h3>
            <div class="image_upload">
                <label class="button_red_square">
                    画像を選択する
                    <input type="file" name="image" accept="image/*" hidden onchange="previewIcon(this)">
                </label>
            </div>

            <div class="input_title">
                <h2>商品の詳細</h2>
            </div>

            <div class="input_area">
                <h3>カテゴリー</h3>
                <div class="categries">
                    @foreach($categries as $category)
                    <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}" class="category_checkbox">
                    <label for="category_{{ $category->id }}" class="category">
                        {{ $category->category }}
                    </label>
                    @endforeach
                </div>

            </div>

            <div class="input_area">
                <h3>商品の状態</h3>
                <div class="condition">
                    <select id="condition_select">
                        <option value="">選択してください</option>
                        @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="input_title">
                <h2>商品名と説明</h2>
            </div>

            <div class="input_area">
                <h3>商品名</h3>
                <input type="text" name="item_name" value="">
            </div>

            <div class="input_area">
                <h3>ブランド名</h3>
                <input type="text" name="brand_name" value="">
            </div>

            <div class="input_area">
                <h3>商品の説明</h3>
                <textarea name="detail"></textarea>
            </div>

            <div class="input_area">
                <h3>販売価格</h3>
                <div class="input_price">
                    <span class="yen">￥</span>
                    <input type="number" name="price" value="">
                </div>
            </div>

            <div class="form-button">
                <button class="button button_submit" type="submit">出品する</button>
            </div>
        </form>
    </div>
</div>
@endsection