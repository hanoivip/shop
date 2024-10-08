<?php

namespace Hanoivip\Shop\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Hanoivip\PaymentMethodContract\IPaymentResult;
use Hanoivip\PaymentContract\Facades\PaymentFacade;
use Hanoivip\Shop\Services\OrderService;

class CheckPendingReceipt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    // 20mins fast check + 500mins slow check
    public $tries = 60;
    
    private $userId;
    
    private $receipt;
    
    private $order;
    /** @var OrderService */
    protected $orderBusiness;
    
    public function __construct($userId, $order, $receipt)
    {
        $this->orderBusiness = app()->make(OrderService::class);
        $this->userId = $userId;
        $this->receipt = $receipt;
        $this->order = $order;
    }
    
    public function handle()
    {
        $lock = Cache::lock("CheckPendingReceipt-shop@$this->userId", 10);
        try {
            $lock->block(5);
            // Lock acquired after waiting maximum of 5 seconds...
            Log::debug("CheckPendingReceipt at shop $this->userId $this->receipt");
            $result = PaymentFacade::query($this->receipt);
            if ($result instanceof IPaymentResult)
            {
                if ($result->isPending())
                {
                    if ($this->attempts() < 10)
                    {
                        $this->release(30);
                    }
                    else
                    {
                        $this->release(60);
                    }
                }
                else if ($result->isFailure())
                {
                    //Log::debug(">> payment is invalid!");
                }
                else
                {
                    $this->orderBusiness->onPayDone($this->order, $this->receipt);
                }
            }
            else
            {
                Log::error("CheckPendingReceipt query transaction $this->receipt error..retry after 10 min");
                $this->release(180);
            }
        } catch (LockTimeoutException $e) {
            $this->release(30);
        } finally {
            optional($lock)->release();
        }
    }
}
