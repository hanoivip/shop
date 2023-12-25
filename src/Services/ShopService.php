<?php
namespace Hanoivip\Shop\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Hanoivip\Vip\Facades\VipFacade;
use Hanoivip\Shop\Models\Shop;
use Hanoivip\Shop\Models\ShopOrder;
use Hanoivip\Game\Facades\GameHelper;
use Exception;
use Hanoivip\Shop\ViewObjects\ShopVO;
use Hanoivip\Shop\Models\ShopItem;
use Illuminate\Support\Str;

class ShopService
{   
    protected $shopData;
    
    protected $orderService;
    
    protected $receiptBusiness;
    
    public function __construct(
        IShopData $shopData, 
        OrderService $orderService,
        ReceiptService $receiptService)
    {
        $this->shopData = $shopData;  
        $this->orderService = $orderService;
        $this->receiptBusiness = $receiptService;
    }
    /**
     * 
     * 
     * @param number $userId
     * @param string $shop Shop Slug
     * @return boolean
     */
    public function canOpen($userId, $shop)
    {
        $shopCfg = $this->shopData->allShop($shop);
        return $this->isUnlock($userId, $shopCfg);
    }
    /**
     * 
     * @param number $uid User ID
     * @param Shop $cfg
     * @return boolean
     */
    protected function isUnlock($uid, $cfg)
    {
        if (empty($cfg))
        {
            throw new Exception("Shop unlock checked on empty config.");
        }
        $unlock = true;
        $conditions = $cfg->unlock;//['unlock']; very trouble some.
        // need auto convert string to array if database source
        // https://stackoverflow.com/questions/53386990/convert-only-one-column-from-string-to-array-in-laravel-5
        // Log::debug('xxx' . print_r($conditions, true));
        foreach ($conditions as $cond)
        {
            $type = $cond->type;//['type'];i donot want to waste my time
            $value = $cond->value;//['value'];it is too strictly
            //$id = $cond['id'];
            switch ($type)
            {
                case 'VipLevel':
                    $unlock = $unlock && $this->checkVipLevel($uid, $value);
                    break;
                case 'AfterTime':
                    $unlock = $unlock && $this->checkAfterTime($value);
                    break;
                case 'BeforeTime':
                    $unlock = $unlock && $this->checkBeforeTime($value);
                    break;
                default:
                    Log::warn("ShopService condition type {$type} is unknown.");
            }
        }
        return $unlock;
    }
    
    /**
     * Lọc ra các shop phù hợp với người chơi.
     * 
     * Lọc dựa trên các điều kiện như:
     * + Thời gian
     * + Điểm VIP
     * + Điểm bất kỳ nào đó ..
     * 
     * @param number $uid
     * @return \stdClass[] Array of shop config
     */
    public function filterUserShops($uid)
    {
        $shopCfgs = $this->shopData->allShop();
        $filtered = [];
        foreach ($shopCfgs as $cfg)
        {
            if ($this->isUnlock($uid, $cfg))
            {
                $filtered[] = $cfg;
            }
        }
        return $filtered;
    }
    
    private function checkVipLevel($uid, $level)
    {
        return VipFacade::getInfo($uid)->level >= $level;
    }
    
    private function checkAfterTime($time)
    {
        return Carbon::now()->timestamp >= $time;
    }
    
    private function checkBeforeTime($time)
    {
        return Carbon::now()->timestamp < $time;
    }
    
    public function getDefaultShop()
    {
        return config('shop.default', '');
    }
    /**
     * 
     * @param string $shop Shop slug
     * @param array|string $items Item code or Array of item codes
     * @return \stdClass[]|\stdClass
     */
    public function getShopItems($shop, $items = null, $orderType = null, $order = null)
    { 
        return $this->shopData->getShopItems($shop, $items, $orderType, $order);
    }
    
    public function newShop($data)
    {
        $name = $data['name'];
        $shop = new ShopVO($name);
        if (!empty($data['starttime']) && !empty($data['endtime']))
        {
            $condition = new \stdClass();
            $condition->type = 'AfterTime';
            $condition->value = intval($data['starttime']);
            $shop->conditions[] = $condition;
            $condition1 = new \stdClass();
            $condition1->type = 'BeforeTime';
            $condition1->value = intval($data['endtime']);
            $shop->conditions[] = $condition1;
        }
        if (!empty($data['viplv']))
        {
            $condition = new \stdClass();
            $condition->type = 'VipLevel';
            $condition->value = intval($data['viplv']);
            $shop->conditions[] = $condition;
        }
        return $this->shopData->newShop($shop);
    }
    
    public function newShopItem($slug, $data)
    {
        //Log::debug(print_r($data, true));
        $shop = $this->shopData->allShop($slug);
        if (empty($shop))
        {
            throw new Exception("Shopv2 shop $shop invalid");
        }
        $code = $data['code'];
        $shopItem = $this->shopData->getShopItems($slug, $code);
        if (!empty($shopItem))
        {
            return __('hanoivip.shop::item.code-duplicated');
        }
        // stick to database data?
        $item = new ShopItem();
        $item->shop_id = $shop->id;
        $item->title = $data['title'];
        $item->code = $code;
        $item->origin_price = $data['origin_price'];
        $item->price = $data['price'];
        $item->currency = $data['currency'];
        $images = [];
        if(!empty($data['images']))
        {
            foreach($data['images'] as $file)
            {
                $name = Str::random().'.'.$file->extension();
                $file->move(public_path('img'), $name);
                $images[] = '/img/'.$name;
            }
        }
        $item->images = json_encode($images);
        $item->description = $data['description'];
        $item->delivery_type = $data['delivery_type'];
        $item->meta = $data['meta'];
        $item->save();
        return true;
    }
    
    public function removeShopItem($slug, $code)
    {
        $items = $this->shopData->getShopItems($slug, $code);
        if ($items instanceof \Illuminate\Database\Eloquent\Collection) 
        {
            if ($items->isNotEmpty())
            {
                foreach ($items as $item)
                {
                    $item->delete();
                }
            }
        }
        else
        {
            if (!empty($items))
            {
                $items->delete();
            }
        }
        return true;
    }
}