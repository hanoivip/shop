<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\Platform\PlatformHelper;

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

    
}