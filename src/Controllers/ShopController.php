<?php

namespace Hanoivip\Shop\Controllers;

use Hanoivip\Activity\Services\ShopService;
use Hanoivip\Platform\PlatformHelper;
use Hanoivip\Shop\Services\IShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ShopController extends Controller
{
    protected $shop;
    
    protected $helper;
    
    protected $shopBusiness;
    
    public function __construct(
        IShop $shop,
        PlatformHelper $helper,
        ShopService $shopBusiness)
    {
        $this->shop = $shop;
        $this->helper = $helper;
        $this->shopBusiness = $shopBusiness;
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
        $platform = $request->input('platform');
        $shops = $this->shop->shopByPlatform($platform);
        $user = Auth::user();
        $userShops = [];
        try
        {
            $userShops = $this->shopBusiness->filterUserShops($user->getAuthIdentifier(), $shops);
        }
        catch (Exception $ex)
        {
            Log::error('Shop get platform shop detail error. Ex:' . $ex->getMessage());
        }
        finally 
        {
            
        }
        if ($request->ajax())
            return ['userShops' => $userShops];
        else
            return view('hanoivip::shop-platform-detail', ['userShops' => $userShops]);
    }
    
    public function buy(Request $request)
    {
        $platform = $request->input('platform');
        $item = $request->input('item');
        $user = Auth::user();
        $error = '';
        $message = '';
        try
        {
            $result = $this->shopBusiness->buy($user->getAuthIdentifier(), $platform, $item);
            if (gettype($result) == 'string')
                $error = $result;
            else
            {
                
            }
        }
        catch (Exception $ex)
        {
            
        }
        finally 
        {
            
        }
    }
}