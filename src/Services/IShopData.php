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
     * @param string $shop Shop Slug
     * @return \stdClass[]
     */
    public function allShop($shop = null);
    /**
     * Get shop items
     * @param string $shop Shop Slug
     * @param string|array $items
     * @param string $order
     * @return \stdClass[]
     */
    public function getShopItems($shop, $items = null, $order = null);
    /**
     * 
     * @param ShopVO $shop
     */
    public function newShop($shop);
}