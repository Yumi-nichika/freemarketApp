<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Category;

class SellController extends Controller
{
    /**
     * 商品出品画面表示
     */
    public function index()
    {
        $conditions = Condition::all();
        $categries = Category::all();

        return view('sell', compact('conditions', 'categries'));
    }
}
