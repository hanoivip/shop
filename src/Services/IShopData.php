<?php

namespace Hanoivip\Shop\Services;

/**
 * 
 * @author gameo
 *
 */
interface IShopData
{
    /**
     * Return all avaiable shop
     * @return \stdClass[]
     */
    public function allShop();
    /**
     * Get shop items
     * @param string $shop
     * @param string|array $items
     * @return \stdClass[]
     */
    public function getShopItems($shop, $items = null);
}