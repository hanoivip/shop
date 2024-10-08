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
    Route::any('/open', 'ShopV2@open')->name('shopv2');
    Route::any('/shop/item', 'ShopV2@viewItem')->name('shopv2.item.view');
    // Cart, max item number is configurable
    Route::any('/cart/remove', 'ShopV2@removeFromCart')->name('shopv2.cart.remove');
    Route::post('/cart/add', 'ShopV2@addToCart')->name('shopv2.cart.add');
    Route::get('/cart', 'ShopV2@viewCart')->name('shopv2.cart');
    // Order
    Route::post('/order', 'ShopV2@order')->name('shopv2.order');
    Route::get('/order/{order}', 'ShopV2@viewOrder')->name('shopv2.order.view');
    // Payment
    //Route::get('/pay/{order}', 'ShopV2@pay')->name('shopv2.pay');
    Route::any('/order-drop', 'ShopV2@dropCart')->name('shopv2.cart.drop');
    // History
    Route::get('/history', 'ShopV2@history')->name('shopv2.history');
});

Route::middleware([
    'web'
])->namespace('Hanoivip\Shop\Controllers')
    ->prefix('shopv2')
    ->group(function () {
        Route::get('/pay/{order}', 'ShopV2@pay')->name('shopv2.pay');
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
        Route::any('/find/order', 'Admin@findOrder')->name('ecmin.shopv2.order.find');
        Route::any('/view/order', 'Admin@viewOrder')->name('ecmin.shopv2.order.view');
        Route::any('/view/order/finish', 'Admin@finishOrder')->name('ecmin.shopv2.order.finish');
        Route::any('/view/order/email', 'Admin@emailOrder')->name('ecmin.shopv2.order.email');
        Route::any('/view/order/check', 'Admin@checkOrder')->name('ecmin.shopv2.order.check');
        Route::any('/new/shop', 'Admin@newShop')->name('ecmin.shopv2.add');
        Route::any('/item/new', 'Admin@newItem')->name('ecmin.shopv2.additem');
        Route::any('/item/del', 'Admin@removeItem')->name('ecmin.shopv2.remitem');
    });