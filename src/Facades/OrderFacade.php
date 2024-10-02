<?php

namespace Hanoivip\Shop\Facades;

use Illuminate\Support\Facades\Facade;
use Hanoivip\Shop\Services\OrderService;

class OrderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OrderService::class;
    }
}
