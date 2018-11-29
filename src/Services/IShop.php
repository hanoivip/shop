<?php

namespace Hanoivip\Shop\Services;
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
     */
    public function activePlatform();
}