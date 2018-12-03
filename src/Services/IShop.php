<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\Models\Shop;
use Hanoivip\Shop\Models\ShopItem;
/**
 * Shop Config Service Interface
 * 
 * @author hanoivip
 *
 */
interface IShop
{
    /**
     * Lấy tất cả các nhóm (platform) có shop
     * + Có dữ liệu về shop
     * + Được cấu hình đầy đủ
     * @return string[]
     */
    public function activePlatform();
    /**
     * Lấy tât cả các shop đang được cấu hình trong platform
     * @param string $platform
     * @return Shop[]
     */
    public function shopByPlatform($platform);
    /**
     * 
     * @param number $shop Shop ID
     * @return ShopItem[]
     */
    public function itemByShop($shop);
    /**
     * 
     * @param string $name
     * @param array Associate array of Shop
     */
    public function getPlatform($name);
}