<?php

namespace Hanoivip\Shop\Services;

/**
 * Cart builder
 * @author GameOH
 *
 */
class PureServiceCart implements ICartService
{
    public function removeFromCart($userId, $itemId)
    {}

    public function getUserCart($userId)
    {}

    public function getDetail($cartId)
    {}

    public function setDeliveryInfo($cart, $info)
    {}

    public function isEmpty($userId)
    {}

    public function addToCart($userId, $shop, $item)
    {}

    public function empty($userId)
    {}

    
}