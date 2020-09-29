<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\Models\Shop;
use Hanoivip\Shop\Models\ShopItem;

class DatabaseShop implements IShopData
{
    public function allShop()
    {
        $shops = Shop::get();
        return $shops;
    }

    public function getShopItems($shop, $items = null)
    {
        if (empty($items))
        {
            // all() : array of eloquent object
            // toArray(): array of array
            $ret = ShopItem::where('shop_id', $shop)->all();
            return $ret;
        }
        else if (gettype($items) == 'array')
        {
            
        }
        else
        {
            return ShopItem::where('shop_id', $shop)->where('code', $items)->first();
        }
    }
    
}