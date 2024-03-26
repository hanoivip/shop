<?php
namespace Hanoivip\Shop\ViewObjects;

use Illuminate\Support\Str;
use Exception;
use Hanoivip\GameContracts\ViewObjects\OrderVO;

class CartVO
{
    public $id;
    
    public $userId;
    
    public $shop;
    
    /**
     * 
     * @var ItemVO[]
     */
    public $items;
    
    public function __construct($userId, $shop)
    {
        $this->userId = $userId;
        $this->shop = $shop;
        $this->id = Str::random(6);
        $this->items = [];
    }
    
    public $delivery_type = null;
    
    public function appendItem($item)
    {
        if (empty($this->delivery_type))
        {
            $this->delivery_type = $item->delivery_type;
        }
        else
        {
            if ($this->delivery_type != $item->delivery_type)
            {
                throw new Exception("Cart multiple delivery type not support");
            }
        }
        $this->items[] = $item;
    }
    
    public $delivery_info = null;
}