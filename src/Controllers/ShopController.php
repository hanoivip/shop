<?php

namespace Hanoivip\Shop\Controllers;

use Hanoivip\Platform\PlatformHelper;
use Hanoivip\Shop\Services\IShop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $shop;
    
    protected $helper;
    
    public function __construct(
        IShop $shop,
        PlatformHelper $helper)
    {
        $this->shop = $shop;
        $this->helper = $helper;
    }
    
    public function listPlatform(Request $request)
    {
        $platforms = $this->shop->activePlatform();
        if ($request->ajax())
            return ['platforms' => $platforms];
        else
            return view('hanoivip::shop-platforms', ['platforms' => $platforms]);
    }
    
    public function detailPlatform(Request $request)
    {
        
    }
    
    public function buy(Request $request)
    {
        
    }
}