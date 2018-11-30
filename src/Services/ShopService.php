<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\Models\Shop;
use Hanoivip\UserBag\Models\UserItem;

class ShopService
{
    /**
     * Lọc ra các shop phù hợp với người chơi
     * 
     * @param number $uid
     * @param Shop[] $shopCfgs
     */
    public function filterUserShops($uid, $shopCfgs)
    {
        
    }
    
    /**
     * Thành công: trả về true
     * Thất bại: trả về lý do
     * 
     * @param number $uid
     * @param string $platform
     * @param string $item
     * @return string|boolean
     */
    public function  buy($uid, $platform, $item)
    {
        
    }
    
    /**
     * Lấy về trạng thái đã mua 1 món đồ
     * 
     * @param number $uid
     * @param Shop[] $shops
     * @return array Item Id => UserItem
     */
    public function getUserBought($uid, $shops)
    {
        
    }
}