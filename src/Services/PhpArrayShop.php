<?php

namespace Hanoivip\Shop\Services;

use Illuminate\Support\Facades\Log;


class PhpArrayShop implements IShopData
{
    public function allShop()
    {
        $shops = config('shops', []);
        $ret = [];
        foreach ($shops as $shop)
        {
            $id = $shop['id'];
            $ret[$id] = (object)$shop;//json_decode(json_encode($shop));
            $ret[$id]->unlock = [];
            foreach ($shop['unlock'] as $cond)
                $ret[$id]->unlock[] = (object)$cond;
        }
        return $ret;
    }
    
    public function getShopItems($shop, $items = null)
    {
        $shops = $this->allShop();
        if (isset($shops[$shop]))
        {
            $ret = [];
            $group = $shops[$shop]->items;
            $itemsCfg = config('shopItems', []);
            if (isset($itemsCfg[$group]))
            {
                foreach ($itemsCfg[$group] as $item)
                {
                    $id = $item['id'];
                    //Log::debug($items . '@' . print_r($item, true));
                    if (!empty($items))
                    {
                        if (gettype($items) == 'string' &&
                            $items == $item['code'])
                        {
                            return (object)$item;
                        }
                        if (gettype($items) == 'array' &&
                            in_array($item['code'], $items))
                        {
                            $ret[$id] = (object)$item;
                        }
                    }
                    else
                    {
                        $ret[$id] = (object)$item;
                    }
                }
            }
            return $ret;
        }
        return null;
    }
    
    public function newShop($shop)
    {}

    
}