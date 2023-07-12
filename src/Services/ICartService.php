<?php

namespace Hanoivip\Shop\Services;

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
     * @param string $cart
     */
    public function getDetail($cart);
    /**
     * Get cart by user
     * @param number $userId
     */
    public function getUserCart($userId);
    
    public function emptyCart($userId);
}