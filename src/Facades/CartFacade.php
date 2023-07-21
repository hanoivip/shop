<?php

namespace Hanoivip\Shop\Facades;

use Illuminate\Support\Facades\Facade;
use Hanoivip\Shop\Services\OrderService;
use Hanoivip\Shop\Services\ICartService;

class CartFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ICartService::class;
    }
}
