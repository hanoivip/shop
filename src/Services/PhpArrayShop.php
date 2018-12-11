<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Platform\PlatformHelper;
use Hanoivip\Shop\Models\Shop;
use Hanoivip\Shop\Models\ShopItem;

class PhpArrayShop implements IShop
{
    protected $platformHelper;
    
    public function __construct(PlatformHelper $helper)
    {
        $this->platformHelper = $helper;
    }
    
    public function shopByPlatform($platform)
    {
        $shops = config('shops.' . $platform, []);
        return $shops;
        // convert to Shop models
        $models = [];
        foreach ($shops as $shop)
        {
            $m = new Shop();
            $m->fill($shop);
            $models[] = $m;
        }
        return $models;
    }

    public function activePlatform()
    {
        $platforms = config('shop.platforms', []);
        $active = [];
        foreach ($platforms as $platform => $cfg)
        {
            $platformName = config('shop.platforms.' . $platform . '.platform');
            $platformObj = $this->platformHelper->getPlatform($platformName);
            if (!empty($platformObj))
                $active[] = $platform;
        }
        return $active;
    }
    
    public function getPlatform($name)
    {
        return config('shop.platforms.' . $name, []);
    }

    public function itemByShop($shop)
    {
        $groups = $shop['items'];
        $items = [];
        foreach ($groups as $group)
        {
            $newItems = config('shopItems.' . $group, []);
            if (!empty($newItems))
                array_push($items, $newItems);
        }
        return $items;
        // convert to models
        $models = [];
        foreach ($items as $item)
        {
            $i = new ShopItem();
            $i->fill($item);
            $models[] = $i;
        }
        return $models;
    }


    
}