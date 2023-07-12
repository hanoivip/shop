<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\Models\Shop;
use Hanoivip\Shop\Models\ShopItem;

class DatabaseShop implements IShopData
{
    public function allShop($shop = null)
    {
        if (empty($shop))
        {
            return Shop::all();
        }
        else if (gettype($shop) == 'array')
        {
            return Shop::whereIn('slug', $shop)->get();
        }
        else
        {
            return Shop::where('slug', $shop)->first();
        }
    }
    
    public function getShopItems($shop, $items = null)
    {
        if (empty($items))
        {
            $shopRecord = Shop::where('slug', $shop)->first();
            if (!empty($shopRecord))
            {
                return $shopRecord->items;
            }
        }
        else if (gettype($items) == 'array')
        {
            return ShopItem::whereIn('code', $items)->where('shop_id', function ($query) use ($shop) {
                $query->select('id')->from('shops')->where('slug', $shop)->first();
            })->get();
        }
        else
        {
            return ShopItem::where('code', $items)->where('shop_id', function ($query) use ($shop) {
                $query->select('id')->from('shops')->where('slug', $shop)->first();
            })->first();
        }
    }
    
}