<?php
namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\ViewObjects\CartVO;
use Hanoivip\Shop\Models\ShopOrder;
use Illuminate\Support\Str;
use Hanoivip\User\Facades\UserFacade;
use Illuminate\Support\Facades\Notification;
use Hanoivip\Shop\Notifications\NewOrder;
use Hanoivip\Shop\Notifications\ItemsSent;
use Exception;
use Hanoivip\Shop\Jobs\SendShopOrderJob;

class OrderService
{   
    const UNPAID = 0;
    //const CANCEL = 1;
    const PAID = 2;
    const ERROR = 3;
    
    const UNSENT = 0;
    const SENDING = 1;
    const SENT = 2;
    const SENDFAIL = 3;
    /**
     * 
     * @param CartVO $cart
     * @return \stdClass
     */
    private function calculatePrice($cart)
    {
        $price = new \stdClass();
        $price->origin_price = 0;
        $price->price = 0;
        $price->currency = null;
        if (!empty($cart->items))
        {   
            foreach ($cart->items as $item)
            {
                $price->origin_price += $item->origin_price * $item->count;
                $price->price += $item->price * $item->count;
                if (empty($price->currency))
                {
                    $price->currency = $item->currency;
                }
                else if ($price->currency != $item->currency)
                {
                    //TODO: might be convert 
                    throw new Exception("Order service not allow multi-currency now");
                }
            }
        }
        return $price;
    }
    
    /**
     * Order by cart
     * @param number $userId
     * @param CartVO $cart
     * @return ShopOrder
     */
    public function order($userId, $cart)
    {
        if (empty($cart->items))
        {
            return __('hanoivip.shop::order.cart-is-empty');
        }
        $price = $this->calculatePrice($cart);
        // save db
        $order = new ShopOrder();
        $order->serial = $serial = Str::random(8);
        $order->user_id = $userId;
        $order->cart = json_encode($cart);
        $order->price = $price->price;
        $order->origin_price = $price->origin_price;
        $order->currency = $price->currency;
        $order->payment_status = self::UNPAID;
        $order->delivery_status = self::UNSENT;
        $order->save();
        $user = UserFacade::getUserCredentials($userId);
        Notification::send($user, new NewOrder($serial, $cart));
        return $order;
    }
    /**
     * 
     * @param string $order Order serial
     * @return ShopOrder
     */
    public function detail($order)
    {
        return ShopOrder::where('serial', $order)->first();
    }
    
    public function list($userId, $page = 0, $count = 10)
    {
        return ShopOrder::where('user_id', $userId)
        ->orderBy('id', 'desc')
        ->paginate($count, ['*'], 'page', $page);
    }
    /**
     * 
     * @param string $order
     * @return boolean
     */
    public function isValid($order)
    {
        $record = $this->detail($order);
        return !empty($record);
    }
    
    public function onPayError($record)
    {
        //$record->payment_status = self::ERROR;
        //$record->save();
    }
    
    public function onPayDone($order, $receipt)
    {
        $orderRec = $this->detail($order);
        if (empty($orderRec))
        {
            return __('hanoivip.shop::order.invalid');
        }
        /*$check = $this->receiptBusiness->check($orderRec->user_id, $order, $receipt);
         if (empty($check))
         {
         return __('hanoivip.shop::receipt.failure');
         }*/
        if ($orderRec->payment_status == self::UNPAID)
        {
            $orderRec->payment_status = self::PAID;
        }
        if ($orderRec->delivery_status == self::UNSENT)
        {
            $orderRec->delivery_status = self::SENDING;
            dispatch(new SendShopOrderJob($order, "ShopV2"));
        }
        $orderRec->save();
        return true;
    }
    
    public function onOrderSent($record, $reason = '')
    {
        $record->delivery_status = OrderService::SENT;
        $record->delivery_reason = $reason;
        $record->save();
        $user = UserFacade::getUserCredentials($record->user_id);
        Notification::send($user, new ItemsSent($record->serial));
        return true;
    }
    
    public function onFinish($order, $reason = '') {
        $record = $this->detail($order);
        if (empty($record))
        {
            return __('hanoivip.shop::order.invalid');
        }
        $record->payment_status = self::PAID;
        $record->delivery_status = OrderService::SENT;
        $record->delivery_reason = $reason;
        $record->save();
        $user = UserFacade::getUserCredentials($record->user_id);
        Notification::send($user, new ItemsSent($record->serial));
        return true;
    }
}