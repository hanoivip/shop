<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\PaymentContract\Facades\PaymentFacade;
use Hanoivip\PaymentMethodContract\IPaymentResult;

class ReceiptService
{
    /**
     * 
     * @param number $userId
     * @param string $order
     * @param string $receipt
     * @return boolean|IPaymentResult
     */
    public function check($userId, $order, $receipt)
    {
        return PaymentFacade::query($receipt);
    }
    
    public function openPendingPage($receipt)
    {
        return PaymentFacade::pendingPage($receipt);
    }
}