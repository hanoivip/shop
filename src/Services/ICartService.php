<?php

namespace Hanoivip\Shop\Services;

interface ICartService
{
    public function addToCart($userId, $shop, $item);
    
    public function removeFromCart($userId, $itemId);
    
    public function getDetail($cart);
}