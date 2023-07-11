<?php
namespace Hanoivip\Shop\ViewObjects;

use Illuminate\Support\Str;

class CartVO
{
    public $id;
    
    public $shop;
    
    /**
     * 
     * @var ItemVO[]
     */
    public $items;
    
    public function __construct($shop)
    {
        $this->shop = $shop;
        $this->id = Str::random(6);
        $this->items = [];
    }
}