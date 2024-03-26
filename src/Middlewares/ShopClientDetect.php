<?php

namespace Hanoivip\User\Middlewares;

use Hanoivip\Shop\Services\ICartService;
use Illuminate\Http\Request;
use Closure;
use Hanoivip\Shop\Services\GameServiceCart;

class ShopClientDetect
{
    
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader("mysdk_version"))
        {
            app()->bind(ICartService::class, GameServiceCart::class);
        }
        return $next($request);
    }
}
