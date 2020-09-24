<?php
use Illuminate\Support\Facades\Route;
// User Domain
Route::middleware([
    'web',
    'auth:web'
])->namespace('Hanoivip\Shop\Controllers')
    ->prefix('user')
    ->group(function () {
    // Liệt kê các shop của người chơi = home of module
    Route::get('/shop', 'ShopController@list')->name('shop');
    // Chọn vật phẩm, sv, nhân vật; hiển thị hóa đơn, html ui
    Route::get('/shop/confirm', 'ShopController@confirm')->name('shop.confirm');
    // Tạo hóa đơn
    Route::post('/shop/order', 'ShopController@order')->name('shop.order');
    // Thanh toán cho hóa đơn
    Route::post('/shop/pay', 'ShopController@pay')->name('shop.pay');
    // Màn hình cảm ơn, goal
    Route::get('/shop/pay-success', 'ShopController@paySuccess')->name('shop.success');
    // Danh sách các hóa đơn, trạng thái
    Route::get('/shop/orders', 'ShopController@history')->name('shop.orders');
});
// Admin Domain
Route::middleware([
    'web',
    'admin'
])->namespace('Hanoivip\Shop\Controllers')
    ->prefix('ecmin')
    ->group(function () {});