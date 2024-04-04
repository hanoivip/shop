<?php

namespace Hanoivip\Shop\Middlewares;

use Hanoivip\Shop\Services\ICartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Closure;
use Hanoivip\Shop\Services\GameServiceCart;

class ShopClientDetect
{
    
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader("mysdk-version"))
        {
            Log::error("ShopClient detect mysdk client.");
            app()->bind(ICartService::class, GameServiceCart::class);
        }
        return $next($request);
    }
}
