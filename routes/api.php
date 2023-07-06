<?php
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->namespace('Hanoivip\Shop\Controllers')
    ->prefix('api')
    ->group(function () {
    });