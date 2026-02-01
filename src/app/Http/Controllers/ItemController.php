<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * 商品一覧画面（トップ画面）表示
     */
    public function index()
    {
        return view('index');
    }
}
