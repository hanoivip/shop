<?php
use Illuminate\Support\Facades\Route;

// IAP migration
Route::middleware('auth:api')->namespace('Hanoivip\Shop\Controllers')
    ->prefix('api')
    ->group(function () {
        Route::any('/iap/items', 'Iap@getItems');
    });