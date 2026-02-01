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

@endsection