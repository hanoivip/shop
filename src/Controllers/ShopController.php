<?php

namespace Hanoivip\Shop\Controllers;

use Hanoivip\Platform\PlatformHelper;
use Hanoivip\Shop\Services\IShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Hanoivip\Shop\Services\ShopService;

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
    
    private function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }
        
        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
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
        $boughts = [];
        try
        {
            $userShops = $this->shopBusiness->filterUserShops($user->getAuthIdentifier(), $shops);
            $boughts = $this->shopBusiness->getUserBought($user->getAuthIdentifier(), $platform);
        }
        catch (Exception $ex)
        {
            Log::error('Shop get platform shop detail error. Ex:' . $ex->getMessage());
        }
        finally 
        {
            
        }
        if ($request->ajax())
            return ['platform' => $platform, 'shops' => $userShops, 'boughts' => $boughts];
        else
            return view('hanoivip::shop-platform-detail', 
                ['platform' => $platform, 'shops' => $userShops, 'boughts' => $boughts]);
    }
    
    public function buy(Request $request)
    {
        $platform = $request->input('platform');
        $shop = $request->input('shop');
        $item = $request->input('item');
        $user = Auth::user();
        $error = '';
        $message = '';
        try
        {
            $result = $this->shopBusiness->buy($user, $platform, $shop, $item);
            if (gettype($result) == 'string')
                $error = $result;
            else
            {
                if ($result)
                {
                    $message = __('shop.buy.success');
                    // event?
                }
                else
                    $error = __('shop.buy.fail');
            }
        }
        catch (Exception $ex)
        {
            $error = __('shop.buy.exception');
        }
        finally 
        {
            
        }
        if ($request->ajax())
            return ['platform' => $platform, 'message' => $message, 'error' => $error];
        else
            return view('hanoivip::shop-buy-result',  ['platform' => $platform, 'message' => $message, 'error' => $error]);
    }
}