<?php
use Illuminate\Support\Facades\Route;

// User Domain
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
    Route::post('/order/drop', 'ShopV2@dropCart')->name('shopv2.cart.drop');
    // History
    Route::get('/history', 'ShopV2@listOrder')->name('shopv2.history');
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
    ->prefix('ecmin/shopv2')
    ->group(function () {
        Route::get('/list/shop', 'Admin@listShop')->name('ecmin.shopv2');
        Route::get('/view/shop', 'Admin@viewShop')->name('ecmin.shopv2.open');
        Route::get('/list/order', 'Admin@listOrder')->name('ecmin.shopv2.order');
        Route::get('/view/order', 'Admin@viewOrder');
        Route::any('/new/shop', 'Admin@newShop')->name('ecmin.shopv2.add');
        Route::any('/new/item', 'Admin@newItem')->name('ecmin.shopv2.additem');
    });