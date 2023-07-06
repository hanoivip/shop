<?php
namespace Hanoivip\Shop\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Hanoivip\Shop\Services\ShopService;
use Hanoivip\Shop\Services\ICartService;
use Hanoivip\Shop\Services\OrderService;

class ShopV2 extends Controller
{

    protected $shopBusiness;
    
    protected $cartBusiness;
    
    protected $orderService;
    
    protected $receiptBusiness;

    public function __construct(
        ShopService $shopBusiness,
        ICartService $cartBusiness,
        OrderService $orderService)
    {
        $this->shopBusiness = $shopBusiness;
        $this->cartBusiness = $cartBusiness;
        $this->orderService = $orderService;
    }
    
    public function list(Request $request)
    {
        $userId = Auth::user()->getAuthIdentifier();
        try {
            $list = $this->shopBusiness->filterUserShops($userId);
        } 
        catch (Exception $ex) 
        {
            
        }
        return view('hanoivip::shop-list', [
            'shops' => $list
        ]);
    }

    public function open(Request $request)
    {
        $shop = $request->input('shop');
        $items = [];
        $message = null;
        $error_message = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            if ($this->shopBusiness->canOpen($userId, $shop))
            {
                $items = $this->shopBusiness->getShopItems($userId, $shop);
            }
            else
            {
                $error_message = __('hanoivip.shop::open.forbidden');
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 open shop exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::open.error');
        }
        return view('hanoivip::view-shop', [
            'items' => $items,
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function addToCart(Request $request)
    {
        $shop = $request->input('shop');
        $item = $request->input('item');
        $message = null;
        $error_message = null;
        try
        {
            $result = $this->cartBusiness->addToCart($userId, $shop, $item);
            if ($result === true)
            {
                $message = __('hanoivip.shop::cart.add.success');
            }
            else
            {
                $error_message = $result;
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 add to cart exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.add.error');
        }
        return view('hanoivip::cart-result', [
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function removeFromCart(Request $request)
    {
        $shop = $request->input('shop');
        $item = $request->input('item');
        $message = null;
        $error_message = null;
        try
        {
            $result = $this->cartBusiness->removeFromCart($userId, $item);
            if ($result === true)
            {
                $message = __('hanoivip.shop::cart.remove.success');
            }
            else
            {
                $error_message = $result;
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 remove from cart exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.remove.error');
        }
        return view('hanoivip::cart-result', [
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function order(Request $request)
    {
        $cart = $request->input('cart');
        $message = null;
        $error_message = null;
        try
        {
            $result = $this->cartBusiness->order($cart);
            if ($result === true)
            {
                $message = __('hanoivip.shop::cart.order.success');
            }
            else
            {
                $error_message = $result;
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 order exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.order.error');
        }
        return view('hanoivip::order-result', [
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function viewOrder(Request $request)
    {
        $order = $request->input('order');
        $record = null;
        $message = null;
        $error_message = null;
        try
        {
            $record = $this->cartBusiness->getRecord($cart);
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 view order exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.view.error');
        }
        return view('hanoivip::order', [
            'record' => $record,
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
    
    public function pay(Request $request)
    {
        $order = $request->input('order');
        $client = null;
        $message = null;
        $error_message = null;
        try
        {
            return PaymentFacade::pay($order, 'shopv2.pay.callback', $client);
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 pay order exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.pay.error');
        }
        return view('hanoivip::pay-order', [
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
    
    public function payCallback(Request $request)
    {
        $order = $request->input('order');
        $receipt = $request->input('receipt');
        $record = $this->orderService->getRecord($order);
        $message = null;
        $error_message = null;
        try
        {
            $result = $this->receiptBusiness->check($record->user_id, $order, $receipt);
            if ($result === true)
            {
                $message = __('hanoivip.shop::pay.success');
            }
            else
            {
                $error_message = $result;
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 pay callback exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::pay.error');
        }
        return view('hanoivip::pay-result', [
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
}