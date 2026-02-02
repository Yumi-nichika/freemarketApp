<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
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
Route::post('/item/{item_id}/mylist', [ItemController::class, 'mylist']);
Route::post('/item/{item_id}/comment', [ItemController::class, 'comment']);

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index']);
    Route::get('/mypage/profile', [MypageController::class, 'edit']);
    Route::post('/mypage/profile', [MypageController::class, 'update']);
});
