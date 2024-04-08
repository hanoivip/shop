<?php
use Illuminate\Support\Facades\Route;

// IAP migration
Route::middleware('auth:api')->namespace('Hanoivip\Shop\Controllers')
    ->prefix('api')
    ->group(function () {
        Route::any('/iap/items', 'Iap@getItems');
        // order by game cart
        Route::any('/iap/order', 'Iap@order');
        // order by detail submission
        Route::any('/iap/orderWithDetail', 'Iap@orderWithDetail');
    });