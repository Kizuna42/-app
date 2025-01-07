<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

// トップページ（商品一覧）
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/?tab=mylist', [ItemController::class, 'mylist'])->name('items.mylist');

// 認証関連
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// 商品関連
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');
Route::middleware('auth')->group(function () {
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});

// 購入関連
Route::middleware('auth')->group(function () {
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchases.address.edit');
    Route::put('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchases.address.update');
    Route::get('/purchase/create/{item}', [PurchaseController::class, 'create'])->name('purchases.create');
});

// マイページ関連
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [UserController::class, 'show'])->name('users.show');
    Route::get('/mypage/profile', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/mypage/profile', [UserController::class, 'update'])->name('users.update');
    Route::get('/mypage?tab=buy', [UserController::class, 'purchases'])->name('users.purchases');
    Route::get('/mypage?tab=sell', [UserController::class, 'items'])->name('users.items');
});

// 商品コメントを保存するためのルート
Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('items.comments.store');

// いいね機能
Route::middleware('auth')->group(function () {
    Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('items.like.toggle');
});
