<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\ViewObjects\CartVO;

interface ICartService
{
    /**
     * 
     * @param number $userId
     * @param number|string $shop Shop slug
     * @param number|string $item Item code
     */
    public function addToCart($userId, $shop, $item);
    
    public function removeFromCart($userId, $itemId);
    /**
     * Get cart by cart ID
     * @param string $cartId
     * @return CartVO
     */
    public function getDetail($cartId);
    /**
     * Get cart by user
     * @param number $userId
     * @return CartVO
     */
    public function getUserCart($userId);
    /**
     * Empty this cart
     * @param number $userId
     * @return boolean
     */
    public function empty($userId);
    
    public function setDeliveryInfo($cart, $info);
    
    public function isEmpty($userId);
}