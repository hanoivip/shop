<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\Models\Shop;
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
}