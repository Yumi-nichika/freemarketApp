@extends('layouts.common')

@section('show-center', 'false')
@section('show-right', 'false')

@section('title')
メール認証画面
@endsection

@section('main')
<div class="content">
    <div class="content-form">
        <form class="form" method="POST" action="/verify-code">
            @csrf

            <div class="form__group">
                <div class="form__group-title">
                    認証コード
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="code" maxlength="4">
                    </div>
                    @error('code')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
            </div>

            <div class="form-button">
                <button class="button button_submit" type="submit">認証する</button>
            </div>
        </form>
    </div>
</div>
@endsection