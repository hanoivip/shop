<?php

namespace Hanoivip\Shop\Controllers;

use Hanoivip\PaymentClient\BalanceUtil;
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
    
    protected $balance;
    
    public function __construct(
        IShop $shop,
        PlatformHelper $helper,
        ShopService $shopBusiness,
        BalanceUtil $balance)
    {
        $this->shop = $shop;
        $this->helper = $helper;
        $this->shopBusiness = $shopBusiness;
        $this->balance = $balance;
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
        $roles = [];
        try
        {
            $info = $this->balance->getInfo($user->getAuthIdentifier());
            $platformObj = $this->helper->getPlatform($platform);
            $roles = $platformObj->getInfos($user);
            Log::debug('SHop query roles..' . print_r($roles, true));
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
            return ['platform' => $platform, 'shops' => $userShops, 'boughts' => $boughts,
                    'balance' => $info, 'roles' => $roles];
        else
            return view('hanoivip::shop-platform-detail', 
                ['platform' => $platform, 'shops' => $userShops, 'boughts' => $boughts, 
                    'balance' => $info, 'roles' => $roles]);
    }
    
    public function buy(Request $request)
    {
        $platform = $request->input('platform');
        $shop = $request->input('shop');
        $item = $request->input('item');
        $role = $request->input('role');
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
                    $message = __('hanoivip::shop.success');
                    // event?
                }
                else
                    $error = __('hanoivip::shop.fail');
            }
        }
        catch (Exception $ex)
        {
            $error = __('hanoivip::shop.exception');
            Log::error('Shop buy item exception. Ex:' . $ex->getMessage());
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