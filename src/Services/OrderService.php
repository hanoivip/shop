<?php
namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\ViewObjects\CartVO;

class OrderService
{
    const UNPAID = 0;
    const CANCEL = 1;
    const PAID = 2;
    
    const UNSENT = 0;
    const SENDFAIL = 1;
    const SENT = 2;
    
    private $shop;
    
    public function __construct(ShopService $shop)
    {
        $this->shop = $shop;
    }
    /**
     * 
     * @param CartVO $cart
     * @return \stdClass
     */
    private function calcualatePrice($cart)
    {
        $price = new \stdClass();
        $price->origin_price = 0;
        $price->price = 0;
        if (!empty($cart->items))
        {   
            foreach ($cart->items as $item)
            {
                $price->origin_price += $item->origin_price * $item->count;
                $price->price += $item->price * $item->count;
            }
        }
        return $price;
    }
    
    /**
     * Order by cart
     * @param CartVO $cart
     * @return ShopOrder
     */
    public function order($userId, $cart)
    {
        $price = $this->calculatePrice($cart);
        $order = new ShopOrder();
        $order->serial = Str::random(8);
        $order->receiver_id = $userId;
        //$order->server = $server;
        //$order->role = $role;
        //$order->shop = $shop;
        //$order->item = $item;
        //$order->count = $count;
        $order->cart = json_encode($cart);
        $order->price = $price->price;
        $order->origin_price = $price->origin_price;
        $order->status = self::UNPAID;
        $order->send_status = self::UNSENT;
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
        
    }
}