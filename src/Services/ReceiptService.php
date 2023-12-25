<?php

namespace Hanoivip\Shop\Services;

use Hanoivip\PaymentContract\Facades\PaymentFacade;
use Hanoivip\PaymentMethodContract\IPaymentResult;

class ReceiptService
{
    private $orders;
    
    public function __construct(OrderService $orders)
    {
        $this->orders = $orders;
    }
    
    /**
     * 
     * @param number $userId
     * @param string $order
     * @param string $receipt
     * @return boolean|IPaymentResult
     */
    public function check($userId, $order, $receipt)
    {
        $record = $this->orders->detail($order);
        if ($record->payment_status == OrderService::PAID)
        {
            return true;
        }
        else if ($record->payment_status == OrderService::ERROR)
        {
            return false;
        }
        /** @var IPaymentResult $result */
        $result = PaymentFacade::query($receipt);
        if ($result->isFailure())
        {
            $this->orders->onPayError($record);
        }
        else if ($result->isSuccess())
        {
            //
        }
        return $result;
    }
    
    public function openPendingPage($receipt)
    {
        return PaymentFacade::pendingPage($receipt);
    }
}