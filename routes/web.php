<?php
use Illuminate\Support\Facades\Route;
// User Domain
Route::middleware([
    'web',
    'auth:web'
])->namespace('Hanoivip\Shop\Controllers')
    ->prefix('shop')
    ->group(function () {
    // Liệt kê các shop của người chơi = home of module
    Route::get('/', 'ShopController@list')->name('shop');
    // Chọn nhân vật, html ui
    Route::any('/helper', 'ShopController@roleHelper')->name('shop.helper');
    // Chọn vật phẩm, sv, nhân vật; hiển thị hóa đơn, html ui == preview order
    Route::any('/confirm', 'ShopController@confirm')->name('shop.confirm');
    // Tạo hóa đơn
    Route::post('/order', 'ShopController@order')->name('shop.order');
    // Thanh toán cho hóa đơn
    Route::get('/pay', 'ShopController@pay')->name('shop.pay');
    // Màn hình cảm ơn, goal
    Route::get('/pay-success', 'ShopController@paySuccess')->name('shop.success');
    // Danh sách các hóa đơn, trạng thái
    Route::get('/orders', 'ShopController@listOrder')->name('shop.orders');
});
// Admin Domain
Route::middleware([
    'web',
    'admin'
])->namespace('Hanoivip\Shop\Controllers')
    ->prefix('ecmin')
    ->group(function () {});