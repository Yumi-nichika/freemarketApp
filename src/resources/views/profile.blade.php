@extends('layouts.common')

@section('show-center', 'true')
@section('show-right', 'true')

@section('title')
プロフィール編集画面（設定画面）
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('main')
<div class="content">
    <h1 class="content-title">
        プロフィール設定
    </h1>
    <div class="content-form">
        <form class="form" action="/mypage/profile" method="post" enctype="multipart/form-data">
            @csrf
            <div class="icon-upload">
                <div class="icon-preview">
                    <img id="iconPreview"
                        src="{{ session('tmp_icon_path') ? asset('storage/' . session('tmp_icon_path'))
                        : ($user_profile->icon_path ? asset('storage/' . $user_profile->icon_path) : asset('img/icon_default.png')) }}" alt="icon">
                </div>
                <label class="button_red_square">
                    画像を選択する
                    <input type="file" name="icon" accept="image/*" hidden onchange="previewIcon(this)">
                </label>
            </div>
            @error('icon')
            <ul class="form-error mb30">
                <li>{{ $message }}</li>
            </ul>
            @enderror

            <div class="form__group mt30">
                <div class="form__group-title">
                    ユーザー名
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" />
                    </div>
                    @error('name')
                    <ul class="form-error">
                        <li>{{ $message }}</li>
                    </ul>
                    @enderror
                </div>
            </div>
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

@section('js')
<script>
    function previewIcon(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('iconPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection