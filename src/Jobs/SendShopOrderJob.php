<?php

namespace Hanoivip\Shop\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Hanoivip\Shop\Services\OrderService;
use Hanoivip\Shop\Models\ShopOrder;
use Hanoivip\Shop\ViewObjects\CartVO;
use Hanoivip\Shop\ViewObjects\ItemVO;

class SendShopOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 10;
    
    private $order;
    
    public function __construct($order)
    {
        $this->order = $order;
    }
    
    public function handle()
    {
        Redis::funnel('SendShopOrderJob@' . $this->order)->limit(1)->then(function () {
            $record = ShopOrder::where('serial', $order)->first();
            if (!empty($record))
            {
                if ($record->payment_status != OrderService::UNPAID)
                {
                    Log::error("Why unpaid order pass here? $this->order");
                }
                if ($record->payment_status == OrderService::PAID &&
                    $record->delivery_status == OrderService::SENDING)
                {
                    /** @var CartVO $cart */
                    $cart = $record->cart;
                    switch ($cart->delivery_type)
                    {
                        case ItemVO::ROLE_CURRENCIES:
                            // send money
                            foreach ($cart->items as $item)
                            {
                                /** @var ItemVO $item */
                                GameHelper::recharge($cart->userId, $cart->delivery_info->svname, $item->code, $item->count, $cart->delivery_info->roleid);
                            }
                            break;
                        case ItemVO::ROLE_ITEMS:
                            // send item
                            foreach ($cart->items as $item)
                            {
                                /** @var ItemVO $item */
                                GameHelper::sendItem($cart->userId, $cart->delivery_info->svname, $item->code, $item->count, $cart->delivery_info->roleid);
                            }
                            break;
                        case ItemVO::WEB_ACCOUNT:
                            // giftcode?
                            break;
                        case ItemVO::GAME_ACCOUNT:
                            break;
                    }
                }
            }
        }, function () {
            // Could not obtain lock...
            return $this->release(60);
        });
    }
}