@extends('layouts.common')

@section('show-center', 'false')
@section('show-right', 'false')

@section('title')
ログイン画面
@endsection

@section('main')
<div class="content">
    <h2 class="content-title">
        ログイン
    </h2>
    <div class="content-form">
        <form class="form" action="/login" method="post" novalidate>
            @csrf
            <div class="form__group">
                <div class="form__group-title">
                    メールアドレス
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="email" name="email" value="{{ old('email') }}" />
                    </div>
                    @error('email')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    パスワード
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="password" name="password" />
                    </div>
                    @error('password')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
            </div>
            <div class="form-button">
                <button class="button button_submit" type="submit">ログインする</button>
                <a class="button button_blue mt30 w100" href="/register">会員登録はこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection