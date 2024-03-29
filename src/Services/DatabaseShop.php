<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Shop\Models\Shop;
use Hanoivip\Shop\Models\ShopItem;
use Hanoivip\Shop\ViewObjects\ShopVO;

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
    
    public function getShopItems($shop, $items = null, $orderType = null, $order = null)
    {
        if (empty($items))
        {
            if (!empty($orderType) && !empty($order))
            {
                return ShopItem::where('shop_id', function ($query) use ($shop) {
                    $query->select('id')->from('shops')->where('slug', $shop)->first();
                })
                ->orderBy($orderType, $order)
                ->get();
            }
            else 
            {
                $shopRecord = Shop::where('slug', $shop)->first();
                if (!empty($shopRecord))
                {
                    return $shopRecord->items;
                }
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
    /**
     * 
     * @param ShopVO $shop
     */
    public function newShop($shop)
    {
        $record = new Shop();
        $record->name = $shop->name;
        $record->slug = $shop->slug;
        $record->conditions = json_encode($shop->conditions);
        $record->save();
    }
}