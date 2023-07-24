<?php
namespace Hanoivip\Shop\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Hanoivip\Shop\Services\ICartService;
use Hanoivip\Shop\Services\OrderService;
use Hanoivip\Shop\Services\ShopService;
use Hanoivip\Shop\Services\ReceiptService;
use Hanoivip\PaymentContract\Facades\PaymentFacade;

class ShopV2 extends Controller
{

    protected $shopBusiness;
    
    protected $cartBusiness;
    
    protected $orderService;
    
    protected $receiptBusiness;

    public function __construct(
        ShopService $shopBusiness,
        ICartService $cartBusiness,
        OrderService $orderService,
        ReceiptService $receiptService)
    {
        $this->shopBusiness = $shopBusiness;
        $this->cartBusiness = $cartBusiness;
        $this->orderService = $orderService;
        $this->receiptBusiness = $receiptService;
    }
    
    public function list(Request $request)
    {
        $userId = Auth::user()->getAuthIdentifier();
        try 
        {
            $list = $this->shopBusiness->filterUserShops($userId);
        } 
        catch (Exception $ex) 
        {
            Log::error("ShopV2 list shop exception: " . $ex->getMessage());
        }
        return view('hanoivip::shopv2-list', [
            'shops' => $list
        ]);
    }

    public function open(Request $request)
    {
        $shop = $request->input('shop');//slug
        $orderType = $request->input('sort_type');//price? meta?
        $order = $request->input('sort');
        $meta = $request->input('is_meta');
        if (!empty($meta) && !empty($orderType))
        {
            $orderType = "meta->$orderType";
        }
        $items = [];
        $message = null;
        $error_message = null;
        $view = view()->exists("hanoivip::shopv2-$shop-view") ? "hanoivip::shopv2-$shop-view" : "hanoivip::shopv2-view";
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            if ($this->shopBusiness->canOpen($userId, $shop))
            {
                //TODO: user dedicated shop items?
                $items = $this->shopBusiness->getShopItems($shop, null, $orderType, $order);
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
        return view($view, [
            'shop' => $shop,
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
        $items = [];
        try
        {
            $items = $this->shopBusiness->getShopItems($shop);
            $userId = Auth::user()->getAuthIdentifier();
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
        return view('hanoivip::shopv2-view', [
            'items' => $items,
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function removeFromCart(Request $request)
    {
        //$shop = $request->input('shop');
        $item = $request->input('item');
        $message = null;
        $error_message = null;
        $record = null;
        try
        {
            $record = $this->cartBusiness->getUserCart($userId);
            $userId = Auth::user()->getAuthIdentifier();
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
        return view('hanoivip::cart', [
            'cart' => $record,
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function viewCart(Request $request)
    {
        $message = null;
        $error_message = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            $record = $this->cartBusiness->getUserCart($userId);
            if (empty($record))
            {
                $error_message = __('hanoivip.shop::cart.view.error');
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 view cart exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.view.error');
        }
        return view('hanoivip::cart', [
            'cart' => $record,
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function dropCart(Request $request)
    {
        $message = null;
        $error_message = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            $this->cartBusiness->empty($userId);
            $message = __('hanoivip.shop::cart.drop.success');
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 drop cart exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.drop.error');
        }
        return response()->redirectToRoute('shopv2');
    }
    
    public function order(Request $request)
    {
        $cart = $request->input('cart');
        $message = null;
        $error_message = null;
        $serial = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            $record = $this->cartBusiness->getDetail($cart);
            if ($record->delivery_type == 1 || $record->delivery_type == 2)
            {
                $svname = $request->input('svname');
                $roleid = $request->input('roleid');
                // TODO: validate ?
                $info = new \stdClass();
                $info->svname = $svname;
                $info->roleid = $roleid;
                $this->cartBusiness->setDeliveryInfo($cart, $info);
            }
            $result = $this->orderService->order($userId, $record);
            if (gettype($result) == 'string')
            {
                $error_message = $result;
            }
            else
            {
                $this->cartBusiness->empty($userId);
                $serial = $result->serial;
                $message = __('hanoivip.shop::cart.order.success');
            }
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 order exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.order.error');
        }
        return view('hanoivip::shopv2-order-result', [
            'serial' => $serial,
            'message' => $message,
            'error_message' => $error_message,
        ]); 
    }
    
    public function viewOrder(Request $request, $order)
    {
        $record = null;
        $message = null;
        $error_message = null;
        try
        {
            $record = $this->orderService->detail($order);
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 view order exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::cart.view.error');
        }
        return view('hanoivip::order', [
            'order' => $record,
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
    
    public function pay(Request $request, $order)
    {
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
        $message = null;
        $error_message = null;
        try
        {
            $result = $this->shopBusiness->onPayDone($order, $receipt);
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
        return view('hanoivip::shop-result', [
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
    
    public function history(Request $request)
    {
        $message = null;
        $error_message = null;
        $page = 0;
        $records = [];
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            $records = $this->orderService->list($userId, $page);
        }
        catch (Exception $ex)
        {
            Log::error("ShopV2 history exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::history.error');
        }
        return view('hanoivip::shopv2-history', [
            'records' => $records,
            'message' => $message,
            'error_message' => $error_message,
        ]);
    }
}