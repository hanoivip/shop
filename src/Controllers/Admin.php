<?php
namespace Hanoivip\Shop\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Hanoivip\Shop\Services\IShopData;
use Hanoivip\Shop\Services\ShopService;

class Admin extends Controller
{
    private $shopData;
    
    public function __construct(IShopData $shopData)
    {
        $this->shopData = $shopData;    
    }
    
    public function listShop(Request $request)
    {
        $userId = Auth::user()->getAuthIdentifier();
        try
        {
            $list = $this->shopData->allShop();
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list shop exception: " . $ex->getMessage());
        }
        return view('hanoivip::shopv2-list', [
            'shops' => $list
        ]);
    }
    
    public function viewShop(Request $request)
    {
        $shop = $request->input('shop');//slug
        $items = [];
        $message = null;
        $error_message = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            $items = $this->shopData->getShopItems($shop);
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin open shop exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::open.error');
        }
        return view('hanoivip::shopv2-view', [
            'items' => $items,
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function listOrder(Request $request)
    {
        try
        {
            $tid = $request->input('tid');
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list shop exception: " . $ex->getMessage());
        }
    }
    
    public function viewOrder(Request $request)
    {
        try
        {
            $order = $request->input('order');
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list shop exception: " . $ex->getMessage());
        }
    }
    
    public function newShop(Request $request)
    {
        try
        {
            if ($request->getMethod() == 'POST')
            {
                
            }
            else 
            {
                return view('hanoivip::shopv2-new');
            }
        }
        catch (Exception $ex)
        {
        }
    }
    
    public function newItem(Request $request)
    {
        try
        {
            $tid = $request->input('tid');
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list shop exception: " . $ex->getMessage());
        }
    }
}