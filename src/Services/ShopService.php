<?php

namespace Hanoivip\Shop\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Hanoivip\Shop\Models\UserShop;
use Hanoivip\PaymentClient\BalanceUtil;
use Hanoivip\Platform\PlatformHelper;
use Illuminate\Auth\Authenticatable;

class ShopService
{
    private $shop;
    
    private $balance;
    
    private $helper;
    
    public function __construct(
        IShop $shop,
        BalanceUtil $balance,
        PlatformHelper $helper)
    {
        $this->shop = $shop;   
        $this->balance = $balance;
        $this->helper = $helper;
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
     * @param array $shopCfgs Array of array of configurations
     */
    public function filterUserShops($uid, $shopCfgs)
    {
        $filtered = [];
        foreach ($shopCfgs as $cfg)
        {
            $unlock = true;
            $conditions = $cfg['unlock'];
            foreach ($conditions as $cond)
            {
                $type = $cond['type'];
                $value = $cond['value'];
                $id = $cond['id'];
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
        return true;
    }
    
    private function checkAfterTime($time)
    {
        return Carbon::now()->timestamp >= $time;
    }
    
    private function checkBeforeTime($time)
    {
        return Carbon::now()->timestamp < $time;
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
    
    /**
     * Lấy về trạng thái đã mua 1 món đồ
     * 
     * @param number $uid
     * @param string $platform
     * @return array array (shop id => array(item id => UserShop))
     */
    public function getUserBought($uid, $platform)
    {
        return [];
        /*
        $tmp = new UserShop();
        $cfg = $this->shop->getPlatform($platform);
        $tmp->setTable($cfg['table']);
        $builder = $tmp->newQuery();
        $all = $builder
                    ->where('user_id', $uid)
                    ->get();
        $boughts = [];
        foreach ($all as $record)
        {
            $shop = $record->shop_id;
            if (!isset($boughts[$shop]))
                $boughts[$shop] = [];
            $boughts[$shop][$record->item_id] = $record;
        }
        return $boughts;*/
    }
    
    
}