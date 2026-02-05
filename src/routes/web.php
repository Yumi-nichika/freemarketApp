<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index']);

Route::get('/item/{item_id}', [ItemController::class, 'show']);

Route::middleware('auth')->group(function () {
    Route::post('/item/{item_id}/like', [ItemController::class, 'like']);
    Route::post('/item/{item_id}/comment', [ItemController::class, 'comment']);

    Route::get('/sell', [SellController::class, 'index']);

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'update']);

    Route::get('/mypage', [MypageController::class, 'index']);
    Route::get('/mypage/profile', [MypageController::class, 'edit']);
    Route::post('/mypage/profile', [MypageController::class, 'update']);
});
