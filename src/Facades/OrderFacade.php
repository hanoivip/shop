<?php

namespace Hanoivip\Shop\Facades;

use Illuminate\Support\Facades\Facade;
use Hanoivip\Shop\Services\OrderService;
use Hanoivip\Shop\Test\TestOrderService;

class OrderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return OrderService::class;
    }
}
