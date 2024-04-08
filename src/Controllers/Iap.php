<?php
namespace Hanoivip\Shop\Controllers;

use Hanoivip\Shop\Services\ICartService;
use Hanoivip\Shop\Services\OrderService;
use Hanoivip\Shop\Services\ShopService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Hanoivip\Shop\Services\PureServiceCart;
use Hanoivip\Shop\ViewObjects\ItemVO;
/**
 * IAP module migration
 * TODO: change domain in app client and remove this
 * 
 * @author GameOH
 *
 */
class Iap extends Controller
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
    
    public function getItems(Request $request)
    {
        $shop = $request->input('client');//= shop slug
        $items = [];
        $message = null;
        $error_message = null;
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            if ($this->shopBusiness->canOpen($userId, $shop))
            {
                $items = $this->shopBusiness->getShopItems($shop, null, null, null);
                // convert to old iap record
                $newItems = [];
                foreach ($items as $item)
                {
                    $newItems[] = [
                        'merchant_id' => $item->code,
                        'disable' => 0,
                        'merchant_title' => $item->title,
                        'merchant_image' => $item->images[0],
                        'price' => $item->price,
                        'currency' => $item->currency,
                    ];
                }
            }
            else
            {
                $error_message = __('hanoivip.shop::open.forbidden');
            }
        }
        catch (Exception $ex)
        {
            Log::error("Iap open shop exception: " . $ex->getMessage());
            $error_message = __('hanoivip.shop::open.error');
        }
        return ['error' => empty($error_message) ? 0 : 1, 'message' => empty($error_message) ? $message : $error_message,
            'data' => ['items' => $newItems]];
    }
    
    public function order(Request $request)
    {
        $cart = $request->input('cart');
        try
        {
            $userId = Auth::user()->getAuthIdentifier();
            $record = $this->cartBusiness->getDetail($cart);
            $result = $this->orderService->order($userId, $record);
            if (gettype($result) == 'string')
            {
                return ['error' => 1, 'message' => $result];
            }
            else
            {
                return ['error' => 0, 'message' => __('hanoivip.shop::cart.order.success'), 'data' => ['serial' => $result->serial] ];
            }
        }
        catch (Exception $ex)
        {
            Log::error("Iap order exception: " . $ex->getMessage());
            return ['error' => 99, 'message' => __('hanoivip.shop::cart.order.error')];
        } 
    }
    
    public function orderWithDetail(Request $request)
    {
        $userId = Auth::user()->getAuthIdentifier();
        $itemRaw = $request->input('item');
        $deliveryRaw = $request->input('delivery');
        try
        {
            $itemJson = json_decode($itemRaw);
            $deliveryInfo = json_decode($deliveryRaw);
            // build cart here
            $cartBuilder = new PureServiceCart($userId);
            $item = new ItemVO($itemJson->code, $itemJson->price, $itemJson->name, $itemJson->currency);
            $cartBuilder->addToCart($userId, null, $item);
            $cartBuilder->setDeliveryInfo(null, $deliveryInfo);
            $record = $cartBuilder->getDetail(null);
            $result = $this->orderService->order($userId, $record);
            if (gettype($result) == 'string')
            {
                return ['error' => 1, 'message' => $result];
            }
            else
            {
                return ['error' => 0, 'message' => __('hanoivip.shop::cart.order.success'), 'data' => ['serial' => $result->serial] ];
            }
        }
        catch (Exception $ex)
        {
            Log::error("Iap order exception: " . $ex->getMessage());
            return ['error' => 99, 'message' => __('hanoivip.shop::cart.order.error')];
        }
    }
}