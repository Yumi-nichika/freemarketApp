@extends('layouts.common')

@section('show-center', 'false')
@section('show-right', 'false')

@section('title')
メール認証誘導画面
@endsection

@section('main')
<div class="content">
    <div class="content-form">
        <div class="form">
            <p>登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。</p>
            <div class="form-button">
                <a href="/verify-code" class="button button_gray">認証はこちらから</a>

                <form method="POST" action="/resend-code">
                    @csrf
                    <button class="button_blue" type="submit">認証メールを再送する</button>
                </form>

                @if(session('message'))
                <p style="color:green;">{{ session('message') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection