@extends('layouts.common')

@section('show-center', 'true')
@section('show-right', 'true')

@section('title')
送付先住所変更画面
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('main')
<div class="content">
    <h1 class="content-title">
        住所の変更
    </h1>
    <div class="content-form">
        <form class="form" action="/purchase/address/{{ $item_id }}" method="post">
            @csrf
            <div class="form__group">
                <div class="form__group-title">
                    郵便番号
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="post_code" value="{{ old('post_code', $user_profile->post_code) }}" />
                    </div>
                    @error('post_code')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    住所
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="address" value="{{ old('address', $user_profile->address) }}" />
                    </div>
                    @error('address')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    建物名
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="building" value="{{ old('building', $user_profile->building) }}" />
                    </div>
                    @error('building')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
            </div>
            <div class="form-button">
                <button class="button button_submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection