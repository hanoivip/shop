<?php

namespace Hanoivip\Shop\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Hanoivip\Vip\Facades\VipFacade;

class ShopService
{
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
        $shopCfgs = $this->shopData->all();
        $filtered = [];
        foreach ($shopCfgs as $cfg)
        {
            $unlock = true;
            $conditions = $cfg->unlock;//['unlock']; very trouble some. 
            // need auto convert string to array if database source
            // https://stackoverflow.com/questions/53386990/convert-only-one-column-from-string-to-array-in-laravel-5
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
     * @return \stdClass[]
     */
    public function getShopItems($shop, $items = [])
    {
        
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
        
    }
    
    /**
     * Thành công: trả về true
     * Thất bại: trả về lý do
     * 
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string $platform Platform name
     * @param number $shop Shop ID
     * @param string $item Item ID
     * @param string $role
     * @return string|boolean
     */
    public function buy($user, $platform, $shop, $item, $role = null)
    {
        $uid = $user->getAuthIdentifier();
        $shops = $this->shop->shopByPlatform($platform);
        if (!isset($shops[$shop]))
            return __('hanoivip::shop.not-exists');
        $shopCfg = $shops[$shop];
        $items = $shopCfg['items'];
        if (!isset($items[$item]))
            return __('hanoivip::shop.item-not-exists');
        $itemCfg = $items[$item];
        // Check price
        $price = $itemCfg['price'];
        if (!$this->balance->enough($uid, $price))
            return __('hanoivip::shop.not-enough-coin');
        // Add item to platform
        $platformObj = $this->helper->getPlatform($platform);
        if (!$platformObj->sendItem($user, $itemCfg['id'], $itemCfg['count'], $role))
            return __('hanoivip::shop.send-item-fail');
        // Charge User
        $this->balance->remove($uid, $price, "Shop:{$shop}:{$item}");
        // Save log
        return true;
    }
    
    
    
}