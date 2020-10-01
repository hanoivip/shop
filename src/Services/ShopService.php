<?php

namespace Hanoivip\Shop\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Hanoivip\Vip\Facades\VipFacade;
use Hanoivip\Shop\Models\ShopOrder;
use Hanoivip\GateClient\Facades\BalanceFacade;
use Hanoivip\Game\Facades\GameHelper;

class ShopService
{
    const UNPAID = 0;
    const CANCEL = 1;
    const PAID = 2;
    
    const UNSENT = 0;
    const SENDFAIL = 1;
    const SENT = 2;
    
    private $shopData;
    
    public function __construct(
        IShopData $shopData)
    {
        $this->shopData = $shopData;   
    }
    
    /**
     * Lọc ra các shop phù hợp với người chơi.
     * 
     * Lọc dựa trên các điều kiện như:
     * + Thời gian
     * + Điểm VIP
     * + Điểm bất kỳ nào đó ..
     * 
     * @param number $uid
     * @return \stdClass[] Array of shop config
     */
    public function filterUserShops($uid)
    {
        $shopCfgs = $this->shopData->allShop();
        $filtered = [];
        foreach ($shopCfgs as $cfg)
        {
            $unlock = true;
            $conditions = $cfg->unlock;//['unlock']; very trouble some. 
            // need auto convert string to array if database source
            // https://stackoverflow.com/questions/53386990/convert-only-one-column-from-string-to-array-in-laravel-5
            //Log::debug(print_r($conditions));
            foreach ($conditions as $cond)
            {
                $type = $cond->type;//['type'];i donot want to waste my time
                $value = $cond->value;//['value'];it is too strictly
                //$id = $cond['id'];
                switch ($type)
                {
                    case 'VipLevel':
                        $unlock = $unlock && $this->checkVipLevel($uid, $value);
                        break;
                    case 'AfterTime':
                        $unlock = $unlock && $this->checkAfterTime($value);
                        break;
                    case 'BeforeTime':
                        $unlock = $unlock && $this->checkBeforeTime($value);
                        break;
                    default:
                        Log::warn("ShopService condition type {$type} is unknown.");
                }
            }
            if ($unlock)
                $filtered[] = $cfg;
        }
        return $filtered;
    }
    
    private function checkVipLevel($uid, $level)
    {
        return VipFacade::getInfo($uid)->level >= $level;
    }
    
    private function checkAfterTime($time)
    {
        return Carbon::now()->timestamp >= $time;
    }
    
    private function checkBeforeTime($time)
    {
        return Carbon::now()->timestamp < $time;
    }
    
    public function getDefaultShop()
    {
        return config('shop.default', '');
    }
    /**
     * 
     * @param string $shop Shop code/name
     * @param array|string $items Item code or Array of item codes
     * @return \stdClass[]|\stdClass
     */
    public function getShopItems($shop, $items = null)
    {
        return $this->shopData->getShopItems($shop, $items);
    }
    /**
     * 
     * @param string $shop
     * @param \stdClass|string $item Item object or item code
     * @param number $count
     * @return \stdClass Price object: price, origin_price
     */
    public function caculatePrice($shop, $item, $count)
    {
        $itemDetail = $item;
        if (gettype($item) == 'string')
            $itemDetail = $this->shopData->getShopItems($shop, $item);
        $price = new \stdClass();
        $price->price = $count * $itemDetail->price;
        $price->origin_price = $count * $itemDetail->origin_price;
        return $price;
    }
    /**
     * 
     * @param number $receiver 
     * @param string $server
     * @param string $role
     * @param string $shop
     * @param string $item
     * @param number $count
     * @return ShopOrder
     */
    public function order($receiver, $server, $role, $shop, $item, $count)
    {
        // Check limit???
        $price = $this->caculatePrice($shop, $item, $count);
        $order = new ShopOrder();
        $order->serial = str_random(8);
        $order->receiver_id = $receiver;
        $order->server = $server;
        $order->role = $role;
        $order->shop = $shop;
        $order->item = $item;
        $order->count = $count;
        $order->price = $price->price;
        $order->origin_price = $price->origin_price;
        $order->status = self::UNPAID;
        $order->send_status = self::UNSENT;
        $order->save();
        return $order;
    }
    /**
     * 
     * @param number $payer Payer user id
     * @param string $serial
     * @return string|boolean True if success, string is fail reason
     */
    public function pay($payer, $serial)
    {
        $order = ShopOrder::where('serial', $serial)->get();
        if ($order->isEmpty())
            return __('shop.order.invalid');
        $order = $order->first();
        if ($order->status != self::UNPAID)
            return __('shop.order.finished');
        $enough = BalanceFacade::enough($payer, $order->price);
        if (empty($enough))
            return __('shop.order.not-enough-money');
        $paid = BalanceFacade::remove($payer, $order->price, "ShopOrder" . $serial);
        if (empty($paid))
            return __('shop.order.charge-error');
        // send item to game
        GameHelper::sendItem($order->receiver_id, $order->server, $order->item, $order->count, $order->role);
        // save status
        $order->status = self::PAID;
        $order->save();
        return true;
    }
    
    public function listOrder($userId)
    {
        return [];
    }
}