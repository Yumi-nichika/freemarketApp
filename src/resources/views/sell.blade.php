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
                @if (session('tmp_item_image_path'))
                <div class="image-preview" id="imagePreviewWrap">
                    <img id="imagePreview" src="{{ asset('storage/' . session('tmp_item_image_path')) }}" alt="">
                </div>
                @else
                <div class="image-preview" id="imagePreviewWrap" style="display:none;">
                    <img id="imagePreview" src="" alt="">
                </div>
                @endif
                <label class="button_red_square">
                    画像を選択する
                    <input type="file" name="item_image" accept="image/*" hidden onchange="previewImage(this)">
                </label>
            </div>
            @error('item_image')
            <ul class="form-error mb30">
                <li>{{ $message }}</li>
            </ul>
            @enderror

            <div class=" input_title">
                <h2>商品の詳細</h2>
            </div>

            <div class="input_area">
                <h3>カテゴリー</h3>
                <div class="categries">
                    @foreach($categries as $category)
                    <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}" class="category_checkbox" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                    <label for="category_{{ $category->id }}" class="category">
                        {{ $category->category }}
                    </label>
                    @endforeach
                </div>
                @error('categories')
                <ul class="form-error">
                    <li>{{ $message }}</li>
                </ul>
                @enderror
            </div>

            <div class="input_area">
                <h3>商品の状態</h3>
                <div class="condition">
                    <select id="condition_select" name="condition_id">
                        <option value="">選択してください</option>
                        @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ (old('condition_id') == $condition->id) ? 'selected' : '' }}>
                            {{ $condition->condition }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @error('condition_id')
                <ul class="form-error">
                    <li>{{ $message }}</li>
                </ul>
                @enderror
            </div>


            <div class="input_title">
                <h2>商品名と説明</h2>
            </div>

            <div class="input_area">
                <h3>商品名</h3>
                <input type="text" name="item_name" value="{{ old('item_name') }}">
                @error('item_name')
                <ul class="form-error">
                    <li>{{ $message }}</li>
                </ul>
                @enderror
            </div>

            <div class="input_area">
                <h3>ブランド名</h3>
                <input type="text" name="brand_name" value="{{ old('brand_name') }}">
                @error('brand_name')
                <ul class="form-error">
                    <li>{{ $message }}</li>
                </ul>
                @enderror
            </div>

            <div class="input_area">
                <h3>商品の説明</h3>
                <textarea name="detail">{{ old('detail') }}</textarea>
                @error('detail')
                <ul class="form-error">
                    <li>{{ $message }}</li>
                </ul>
                @enderror
            </div>

            <div class="input_area">
                <h3>販売価格</h3>
                <div class="input_price">
                    <span class="yen">￥</span>
                    <input type="number" name="price" value="{{ old('price') }}">
                </div>
                @error('price')
                <ul class="form-error">
                    <li>{{ $message }}</li>
                </ul>
                @enderror
            </div>

            <div class="form-button">
                <button class="button button_submit" type="submit">出品する</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const file = input.files[0];
            const preview = document.getElementById('imagePreview');
            const wrap = document.getElementById('imagePreviewWrap');

            reader.onload = function(e) {
                preview.src = e.target.result;
                wrap.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection