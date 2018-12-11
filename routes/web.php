<?php

use Illuminate\Support\Facades\Route;
// User Domain
Route::middleware(['web', 'auth:web'])->namespace('Hanoivip\Shop\Controllers')->prefix('user')->group(function () {
    Route::get('/shop', function () {
        return redirect()->route('shop.platform');
    })->name('shop');
    // Liệt kê các nền tảng có shop
    Route::get('/shop/group', 'ShopController@listPlatform')->name('shop.platform');
    // Chi tiết shop trên 1 nền tảng
    Route::get('/shop/group/detail', 'ShopController@detailPlatform')->name('shop.platform.detail');
    // Yêu cầu mua
    Route::post('/shop/group/buy', 'ShopController@buy')->name('shop.buy');
});
// Admin Domain
Route::middleware(['web', 'admin'])->namespace('Hanoivip\Shop\Controllers')->prefix('ecmin')->group(function () {

});