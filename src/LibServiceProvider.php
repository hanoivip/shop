<?php

namespace Hanoivip\Shop;

use Illuminate\Support\ServiceProvider;
use Hanoivip\Shop\Services\DatabaseShop;
use Hanoivip\Shop\Services\IShopData;
use Hanoivip\Shop\Services\PhpArrayShop;
use Hanoivip\Shop\Services\ICartService;
use Hanoivip\Shop\Services\SessionCart;

class LibServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            //__DIR__.'/../config/shops.php' => config_path('shops.php'),
            __DIR__.'/../config/shop.php' => config_path('shop.php'),
            //__DIR__.'/../config/shopItems.php' => config_path('shopItems.php'),
            __DIR__.'/../views' => resource_path('views/vendor/hanoivip'),
            __DIR__.'/../lang' => resource_path('lang/vendor/hanoivip'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'hanoivip.shop');
        $this->loadViewsFrom(__DIR__ . '/../views', 'hanoivip');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        //$this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
    
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shop.php', 'shop');
        //$this->mergeConfigFrom(__DIR__.'/../config/shops.php', 'shops');
        //$this->mergeConfigFrom(__DIR__.'/../config/shopItems.php', 'shopItems');
        if (config('shop.cfg') == 'array')
        {
            //$this->app->bind(IShopData::class, PhpArrayShop::class);
        }
        if (config('shop.cfg') == 'database')
        {
            //$this->app->bind(IShopData::class, DatabaseShop::class);
        }
        $this->app->bind(IShopData::class, DatabaseShop::class);
        $this->app->bind(ICartService::class, SessionCart::class);
    }
}
