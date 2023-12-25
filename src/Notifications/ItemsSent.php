<?php

namespace Hanoivip\Shop\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ItemsSent extends Notification implements ShouldQueue
{
    use Queueable;
    
    private $order;
    
    public function __construct($order)
    {
        $this->order = $order;
    }
    
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)->view('hanoivip::emails.items-sent', ['order' => $this->order]);
    }
    
    public function toArray($notifiable)
    {
        return ['order'=>$this->order];
    }
}
