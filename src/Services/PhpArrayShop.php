<?php

namespace Hanoivip\Shop\Services;


class PhpArrayShop implements IShopData
{
    public function allShop()
    {
        $shops = config('shops', []);
        $ret = [];
        foreach ($shops as $id => $shop)
        {
            $ret[$id] = json_decode(json_encode($shop));
        }
        return $ret;
    }
    
    public function getShopItems($shop, $items)
    {
        $shops = config('shops', []);
        $ret = [];
        if (isset($shops[$shop]))
        {
            $group = $shops[$shop]->items;
            $items = config('shopItems', []);
            if (isset($items[$group]))
            {
                foreach ($items as $id => $item)
                {
                    $ret[$id] = json_decode(json_encode($item));
                }
            }
        }
        return $ret;
    }
    
}