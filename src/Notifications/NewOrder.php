<?php

namespace Hanoivip\Shop\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Hanoivip\Shop\Models\ShopOrder;
use Hanoivip\Shop\ViewObjects\CartVO;

class NewOrder extends Notification implements ShouldQueue
{
    use Queueable;
    
    private $order;
    /**
     * 
     * @var CartVO $cart
     */
    private $cart;
    
    public function __construct($order, $cart)
    {
        $this->order = $order;
        $this->cart = $cart;
    }
    
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)->view('hanoivip::emails.order-new', ['order' => $this->order, 'cart' => $this->cart]);
    }
    
    public function toArray($notifiable)
    {
        return ['order'=>$this->order];
    }
}
