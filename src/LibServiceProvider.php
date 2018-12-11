<?php

namespace Hanoivip\Shop;

use Illuminate\Support\ServiceProvider;
use Hanoivip\Shop\Services\DatabaseShop;
use Hanoivip\Shop\Services\IShop;
use Hanoivip\Shop\Services\PhpArrayShop;

class LibServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/shops.php' => config_path('shops.php'),
            __DIR__.'/../config/shop.php' => config_path('shop.php'),
            __DIR__.'/../views' => resource_path('views/vendor/hanoivip'),
            __DIR__.'/../lang' => resource_path('lang/vendor/hanoivip'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom( __DIR__.'/../lang', 'hanoivip');
        $this->loadViewsFrom(__DIR__ . '/../views', 'hanoivip');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
    
    public function register()
    {
        if (config('shop.cfg') == 'array')
        {
            $this->app->bind(IShop::class, PhpArrayShop::class);
        }
        if (config('shop.cfg') == 'database')
        {
            $this->app->bind(IShop::class, DatabaseShop::class);
        }
        $this->mergeConfigFrom(__DIR__.'/../config/shop.php', 'shop');
        $this->mergeConfigFrom(__DIR__.'/../config/shops.php', 'shops');
        $this->mergeConfigFrom(__DIR__.'/../config/shopItems.php', 'shopItems');
    }
}
