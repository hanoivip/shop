<?php
namespace Hanoivip\Shop\ViewObjects;

class ItemVO
{
    public $code;
    
    public $name;
    
    public $description;
    
    public $images;
    
    public $origin_price;
    
    public $price;
    
    public $count;
    
    public $currency;
    /**
     * 0 - Undefined
     * 1 - Send to game role item bags
     * 2 - Send to game role currency bags
     * 3 - Send to web account
     * 4 - Send to game account
     * @var number
     */
    public $delivery_type;
}