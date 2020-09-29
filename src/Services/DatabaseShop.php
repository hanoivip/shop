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
            $ret = ShopItem::where('shop_id', $shop)->get();
            return $ret;
        }
        else if (gettype($items) == 'array')
        {
            
        }
        else
        {
            
        }
    }
    
}