<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\VerificationController;

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

Route::middleware(['auth'])->group(function () {
    // 認証待ち画面へアクセスしたときに resendAndShow メソッドを呼ぶ
    Route::get('/email-sent', [VerificationController::class, 'resendAndShow'])
        ->name('verification.notice');
        
    Route::get('/verify-code', function () {
        return view('auth.verify-code');
    });
    Route::post('/verify-code', [VerificationController::class, 'verify']);
    Route::post('/resend-code', [VerificationController::class, 'resend']);
});


Route::get('/', [ItemController::class, 'index']);
Route::get('/search', [ItemController::class, 'search']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);


Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/item/{item_id}/like', [ItemController::class, 'like']);
    Route::post('/item/{item_id}/comment', [ItemController::class, 'comment']);

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);

    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'update']);

    Route::get('/sell', [SellController::class, 'show']);
    Route::post('/sell', [SellController::class, 'store']);

    Route::get('/mypage', [MypageController::class, 'show']);
    Route::get('/mypage/profile', [MypageController::class, 'edit']);
    Route::post('/mypage/profile', [MypageController::class, 'update']);
});
