<?php

namespace Hanoivip\Shop\Services;

use Exception;
use Hanoivip\Shop\ViewObjects\CartVO;

/**
 * Cart builder
 * @author GameOH
 *
 */
class PureServiceCart implements ICartService
{
    protected $cart;
    
    public function __construct($userId)
    {
        $this->cart = new CartVO($userId, "pure");
    }
    
    public function removeFromCart($userId, $itemId)
    {
        throw new Exception("Not supported operation");
    }

    public function getUserCart($userId)
    {
        throw new Exception("Not supported operation");
    }

    public function getDetail($cartId)
    {
        return $this->cart;
    }

    public function setDeliveryInfo($cart, $info)
    {
        $this->cart->delivery_info = $info;
    }

    public function isEmpty($userId)
    {
        throw new Exception("Not supported operation");
    }

    public function addToCart($userId, $shop, $item)
    {
        $this->cart->appendItem($item);
    }

    public function empty($userId)
    {
        throw new Exception("Not supported operation");
    }

    
}