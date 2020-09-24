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
    public function all();
}