<?php

namespace Hanoivip\Shop\Services;

use Exception;
use Hanoivip\GameContracts\Contracts\IGameOperator;
use function Illuminate\Foundation\Testing\Concerns\__construct;
use Illuminate\Support\Facades\Auth;
use Hanoivip\Shop\ViewObjects\CartVO;
use Hanoivip\Shop\ViewObjects\ItemVO;
use Hanoivip\Game\Facades\GameHelper;

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
        $serverId = null;
        if (str_contains($cartId, "@@"))
        {
            $i = strstr($cartId, "@@");
            $serverId = substr($cartId, 0, $i);
            $cartId = substr($cartId, $i+2);
        }
        $order = GameHelper::getOrderDetail($cartId, $serverId);
        // convert into CartVO
        if ($order->uid != $user->getAuthIdentifier())
        {
            throw new Exception("Order is belong to other???");
        }
        $cart = new CartVO($order->uid, "gameservice");
        $cart->id = $order->id;
        $item = new ItemVO($order->item, $order->amount, $order->title, $order->currency);
        $item->delivery_type = ItemVO::ROLE_CURRENCIES;
        $cart->appendItem($item);
        // delivery info
        $info = new \stdClass();
        $info->svname = "s$order->server";
        $info->roleid = $order->role;
        $cart->delivery_info = $info;
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