<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\Services\ICartService;
use Hanoivip\Shop\ViewObjects\CartVO;

/**
 * Cart implement by session
 * With limited items
 * 
 * @author GameOH
 *
 */
class SessionCart implements ICartService
{
    private $shopBusiness; 
    
    public function __construct(ShopService $shopBusiness)
    {
        $this->shopBusiness = $shopBusiness;
    }
    
    public function addToCart($userId, $shop, $item)
    {
        $key = "ShopCart@" . $userId;
        if (!session()->has($key))
        {
            $cart = new CartVO($shop);
            session()->put($cart->id, $key);
        }
        else
        {
            $cart = session()->get($key, null);
        }
        if (empty($cart))
        {
            throw new Exception(__("hanoivip.shop::cart.empty"));
        }
        if (!$this->shopBusiness->canOpen($userId, $shop))
        {
            throw new Exception(__('hanoivip.shop::open.forbidden'));
        }
        $itemRecord = $this->shopBusiness->getShopItems($shop, $item);
        if (empty($itemRecord))
        {
            throw new Exception(__('hanoivip.shop::item.empty'));
        }
        $max = config('shop.cart.max', 1);
        if (count($cart->items) >= $max)
        {
            return __("hanoivip.shop::cart.full");
        }
        $cart->items[] = $itemRecord;
        //save
        session()->put($key, $cart);
        return true;
    }
    
    public function removeFromCart($userId, $itemId)
    {
        $key = "ShopCart@" . $userId;
        if (!session()->has($key))
        {
            throw new Exception(__("hanoivip.shop::cart.empty"));
        }
        else
        {
            $cart = session()->get($key, null);
        }
        if (empty($cart) || empty($cart->items))
        {
            throw new Exception(__("hanoivip.shop::cart.empty"));
        }
        $dindex = -1;
        foreach ($cart->items as $index => $item)
        {
            if ($item->code == $itemId)
            {
                $dindex = $index; break;
            }
        }
        if ($dindex > 0)
        {
            unset($cart->items[$dindex]);
            //save
            session()->put($key, $cart);
        }
        return true;
    }
    
    public function getDetail($cart)
    {
        if (session()->has($cart))
        {
            $key = session()->get($cart, null);
            if (!empty($key) && session()->has($key))
            {
                $cart = session()->get($key, null);
                if (empty($cart))
                {
                    throw new Exception(__("hanoivip.shop::cart.empty"));
                }
                return $cart;
            }
        }
    }
}