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
    
Route::middleware([
    'web',
    'auth:web'
])->namespace('Hanoivip\Shop\Controllers')
->prefix('shopv2')
->group(function () {
    // Test
    Route::get('/list', 'ShopV2@list');
    // Open shop, param: shop ID
    Route::get('/open', 'ShopV2@open')->name('shopv2');
    // Cart, max item number is configurable
    Route::post('/cart/remove', 'ShopV2@removeFromCart')->name('shopv2.cart.remove');
    Route::post('/cart/add', 'ShopV2@addToCart')->name('shopv2.cart.add');
    Route::get('/cart', 'ShopV2@viewCart')->name('shopv2.cart');
    // Order
    Route::post('/order', 'ShopV2@order')->name('shopv2.order');
    Route::get('/order/{order}', 'ShopV2@viewOrder')->name('shopv2.order.view');
    // Payment
    Route::get('/pay/{order}', 'ShopV2@pay')->name('shopv2.pay');
    //Route::post('/pay/callback', 'ShopV2@payCallback')->name('shopv2.pay.callback');
    Route::post('/order/drop', 'ShopV2@dropCart')->name('shopv2.cart.drop');
});

Route::middleware([
    'web'
])->namespace('Hanoivip\Shop\Controllers')
->prefix('shopv2')
->group(function () {
    Route::get('/pay-callback', 'ShopV2@payCallback')->name('shopv2.pay.callback');
});
    
// Admin Domain
Route::middleware([
    'web',
    'admin'
])->namespace('Hanoivip\Shop\Controllers')
    ->prefix('ecmin')
    ->group(function () {});