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
    
    private $shopBusiness;
    
    public function __construct(
        IShopData $shopData,
        ShopService $shopBusiness)
    {
        $this->shopData = $shopData;
        $this->shopBusiness = $shopBusiness;
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
        return view('hanoivip::admin.shopv2-list', [
            'shops' => $list
        ]);
    }
    
    public function viewShop(Request $request)
    {
        $slug = $request->input('slug');//slug
        $items = [];
        $message = null;
        $error_message = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            $items = $this->shopData->getShopItems($slug);
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin open shop exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::open.error');
        }
        return view('hanoivip::admin.shopv2-view', [
            'slug' => $slug,
            'items' => $items,
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function listOrder(Request $request)
    {
        $message = null;
        $error_message = null;
        $page = 0;
        $records = [];
        $tid = $request->input('tid');
        try
        {
            $records = $this->orderService->list($tid, $page);
        }
        catch (Exception $ex)
        {
            
        }
        return view('hanoivip::shopv2-history', [
            'records' => $records,
            'message' => $message,
            'error_message' => $error_message,
        ]);
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
                $message = null;
                $error_message = null;
                $result = $this->shopBusiness->newShop($request->all());
                if ($result)
                {
                    $message = "success";
                }
                else
                {
                    $error_message = "failure";
                }
                return view('hanoivip::admin.result', [
                    'message' => $message,
                    'error_message' => $error_message,
                ]);
            }
            else 
            {
                return view('hanoivip::admin.shopv2-new');
            }
        }
        catch (Exception $ex)
        {
        }
    }
    
    public function delShop(Request $request)
    {
        try
        {
            
        }
        catch (Exception $ex)
        {
        }
    }
    
    public function newItem(Request $request)
    {
        $slug = $request->input('slug');
        try
        {
            if ($request->getMethod() == 'POST')
            {
                $message = null;
                $error_message = null;
                $result = $this->shopBusiness->newShopItem($slug, $request->all());
                if ($result)
                {
                    $message = "success";
                }
                else
                {
                    $error_message = "failure";
                }
                return view('hanoivip::admin.result', [
                    'message' => $message,
                    'error_message' => $error_message,
                ]);
            }
            else
            {
                return view('hanoivip::admin.shopv2-new-item', ['slug' => $slug]);
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 admin list shop exception: " . $ex->getMessage());
        }
    }
}