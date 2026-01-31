<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

@php
$showCenter = View::getSection('show-center') === 'true';
$showRight = View::getSection('show-right') === 'true';
@endphp

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__left">
                <img src="{{ asset('img/logo.png') }}">
            </div>

            <div class="header__center">
                <input type="text" class="header__center-txt {{ $showCenter ? '' : 'is-hidden' }}" placeholder="なにをお探しですか？">
            </div>

            <div class="header__right ">
                <div class="{{ $showRight ? '' : 'is-hidden' }}">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="button button_black">ログアウト</button>
                    </form>
                    <a class="button button_black" href="/mypage">マイページ</a>
                    <a class="button button_white" href="/sell">出品</a>
                </div>
            </div>
        </div>
    </header>
    <main>
        @yield('main')
    </main>
    @yield('js')
</body>

</html>