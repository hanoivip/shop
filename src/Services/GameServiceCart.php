<?php

namespace Hanoivip\Shop\Services;

use Exception;
use Hanoivip\GameContracts\Contracts\IGameOperator;
use function Illuminate\Foundation\Testing\Concerns\__construct;
use Illuminate\Support\Facades\Auth;
use Hanoivip\GameContracts\ViewObjects\UserVO;
use Hanoivip\Shop\ViewObjects\CartVO;
use Hanoivip\Shop\ViewObjects\ItemVO;

/**
 * Cart implement by game service
 * 
 * @author GameOH
 *
 */
class GameServiceCart implements ICartService
{
    protected $operator;
    
    public function __construct(IGameOperator $ops)
    {
        $this->operator = $ops;
    }
    
    public function addToCart($userId, $shop, $item)
    {
        throw new Exception("Not supported operation");
    }
    
    public function removeFromCart($userId, $itemId)
    {
        throw new Exception("Not supported operation");
    }
    
    public function getDetail($cartId)
    {
        $user = Auth::user();
        $order = $this->operator->orderDetail(new UserVO($user->getAuthIdentifier(), $user->getAuthIdentifierName()), $cartId);
        // convert into CartVO
        if ($order->uid != $user->getAuthIdentifier())
        {
            throw new Exception("Order is belong to other???");
        }
        $cart = new CartVO($order->uid, "gameservice");
        $cart->id = $order->id;
        $item = new ItemVO($order->item, $order->amount, $order->currency);
        $item->delivery_type = ItemVO::ROLE_CURRENCIES;
        $cart->appendItem($item);
        return $cart;
    }

    public function getUserCart($userId)
    {
        throw new Exception("Not supported operation");
    }
    
    public function empty($userId)
    {
        throw new Exception("Not supported operation");
    }
    
    public function setDeliveryInfo($cart, $info)
    {
        throw new Exception("Not supported operation");
    }
    
    public function isEmpty($userId)
    {

    }
}