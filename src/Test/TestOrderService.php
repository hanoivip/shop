<?php
namespace Hanoivip\Shop\Test;

use Hanoivip\Shop\Jobs\SendShopOrderJob;
use Hanoivip\Shop\Models\ShopOrder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TestOrderService
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
        return 666;
    }
    
    /**
     * Order by cart
     * @param number $userId
     * @param CartVO $cart
     * @return ShopOrder
     */
    public function order($userId, $cart)
    {
        $price = $this->calculatePrice($cart);
        // save db
        $order = new ShopOrder();
        $order->serial = Str::random(8);
        $order->user_id = $userId;
        $order->cart = json_encode($cart);
        $order->price = $price->price;
        $order->origin_price = $price->origin_price;
        $order->currency = $price->currency;
        $order->payment_status = self::UNPAID;
        $order->delivery_status = self::UNSENT;
        return $order;
    }
    /**
     * 
     * @param string $order Order serial
     * @return ShopOrder
     */
    public function detail($order)
    {
        $order = new ShopOrder();
        $order->serial = $order;
        $order->user_id = Auth::user()->getAuthIdentifier();
        $order->cart = json_encode([]);
        $order->price = 666;
        $order->origin_price = 6666;
        $order->currency = "USD";
        $order->payment_status = self::UNPAID;
        $order->delivery_status = self::UNSENT;
        return $order;
    }
    
    public function list($userId, $page = 0, $count = 10)
    {
        return ShopOrder::where('user_id', $userId)
        ->skip($page * $count)
        ->take($count)
        ->get();
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
        $record->payment_status = self::ERROR;
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
        //$orderRec->save();
        return true;
    }
    
    public function onOrderSent($record, $reason = '')
    {
        $record->delivery_status = TestOrderService::SENT;
        $record->delivery_reason = $reason;
        //$record->save();
        return true;
    }
}