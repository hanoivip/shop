<?php

namespace Hanoivip\Shop\Mails;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Hanoivip\Shop\Models\ShopOrder;

class NewOrder extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;
    
    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $order = ShopOrder::where('serial', $this->order)->first();
        return $this->view('hanoivip::shopv2.order.new', ['cart' => $order->cart]);
    }
}
