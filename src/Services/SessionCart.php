<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\ViewObjects\CartVO;
use Illuminate\Support\Facades\Log;
use Exception;

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
            $cart = new CartVO($userId, $shop);
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
            throw new Exception(__('hanoivip.shop::item.not-found'));
        }
        $max = config('shop.cart.max', 1);
        if (count($cart->items) >= $max)
        {
            return __("hanoivip.shop::cart.full");
        }
        $itemRecord->count = 1;
        $cart->appendItem($itemRecord);
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
        if ($dindex > -1)
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
    /**
     * 
     * {@inheritDoc}
     * @see \Hanoivip\Shop\Services\ICartService::getUserCart()
     */
    public function getUserCart($userId)
    {
        $key = "ShopCart@" . $userId;
        if (session()->has($key))
        {
            return session()->get($key, null);
        }
    }
    
    public function empty($userId)
    {
        $cart = $this->getUserCart($userId);
        if (!empty($cart))
        {
            Log::debug("Cart empty player $userId");
            $key = "ShopCart@" . $userId;
            session()->put($cart->id, null);
            session()->put($key, null);
        }
    }
    
    public function setDeliveryInfo($cart, $info)
    {
        $key = session()->get($cart, null);
        if (!empty($key))
        {
            $record = session()->get($key, null);
            $record->delivery_info = $info;
            session()->put($key, $record);
        }
    }
    
    public function isEmpty($userId)
    {
        $key = "ShopCart@" . $userId;
        $cart = null;
        if (session()->has($key))
        {
            $cart = session()->get($key, null);
        }
        return empty($cart);
    }
}