<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;

class ProductController extends Controller
{
    /**
     * 商品一覧画面（トップ画面）表示
     */
    public function index()
    {
        return view('index');
    }
}
