<?php

namespace Hanoivip\Shop\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Hanoivip\Shop\Services\OrderService;
use Hanoivip\Shop\ViewObjects\ItemVO;
use Hanoivip\Game\Facades\GameHelper;

class SendShopOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 10;
    
    private $order;
    
    private $reason;
    /** @var OrderService */
    private $orderService;
    
    public function __construct($order, $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
        $this->orderService = app()->make(OrderService::class);
    }
    
    public function handle()
    {
        Redis::funnel('SendShopOrderJob@' . $this->order)->limit(1)->then(function () {
            $record = $this->orderService->detail($this->order);
            if (!empty($record))
            {
                /*if ($record->payment_status == OrderService::UNPAID)
                {
                    Log::error("Why unpaid order pass here? $this->order");
                }
                if ($record->payment_status == OrderService::PAID &&
                    $record->delivery_status == OrderService::SENDING)*/
                if ($record->delivery_status == OrderService::SENDING)
                {
                    $sent = true;
                    /** @var CartVO $cart */
                    $cart = $record->cart;
                    //print_r($cart);
                    switch ($cart->delivery_type)
                    {
                        case ItemVO::ROLE_CURRENCIES:
                            // send money
                            foreach ($cart->items as $item)
                            {
                                /** @var ItemVO $item */
                                $sent = $sent && GameHelper::recharge($cart->userId, $cart->delivery_info->svname, $item->code, $cart->delivery_info->roleid);
                            }
                            break;
                        case ItemVO::ROLE_ITEMS:
                            // send item
                            foreach ($cart->items as $item)
                            {
                                /** @var ItemVO $item */
                                $sent = $sent && GameHelper::sendItem($cart->userId, $cart->delivery_info->svname, $item->code, $item->count, $cart->delivery_info->roleid);
                            }
                            break;
                        case ItemVO::WEB_ACCOUNT:
                            // giftcode?
                            break;
                        case ItemVO::GAME_ACCOUNT:
                            break;
                        case ItemVO::XGAME_ACC:
                            // buy account, transfer account, exchange account
                            $sent = $sent && GameHelper::transferAccount($item->code, $cart->userId);
                            break;
                    }
                    if ($sent === true)
                    {
                        $this->orderService->onOrderSent($record, $this->reason);
                    }
                    else
                    {
                        // retry after 1 minutes
                        $this->release(60);
                    }
                }
            }
        }, function () {
            // Could not obtain lock...
            return $this->release(60);
        });
    }
}