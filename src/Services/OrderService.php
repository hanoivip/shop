<?php
namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\ViewObjects\CartVO;
use Hanoivip\Shop\Models\ShopOrder;
use Illuminate\Support\Str;

class OrderService
{   
    const UNPAID = 0;
    //const CANCEL = 1;
    const PAID = 2;
    
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
        $order = new ShopOrder();
        $order->serial = Str::random(8);
        $order->user_id = $userId;
        $order->cart = json_encode($cart);
        $order->price = $price->price;
        $order->origin_price = $price->origin_price;
        $order->currency = $price->currency;
        $order->payment_status = self::UNPAID;
        $order->delivery_status = self::UNSENT;
        $order->save();
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
    
    public function list($userId, $page = 0)
    {
        
    }
}