<?php
namespace Hanoivip\Shop\ViewObjects;

class ItemVO
{
    const ROLE_CURRENCIES = 1;
    const ROLE_ITEMS = 2;
    const WEB_ACCOUNT = 3;
    const GAME_ACCOUNT = 4;
    const XGAME_ACC = 5;// change game account..
    
    public $code;
    
    public $name;
    
    public $title;
    
    public $description;
    
    public $images;
    
    public $origin_price;
    
    public $price;
    
    public $count;
    
    public $currency;
    /**
     * 0 - Undefined
     * 1 - Send to game role currency bags
     * 2 - Send to game role item bags
     * 3 - Send to web account
     * 4 - Send to game account
     * @var number
     */
    public $delivery_type;
    
    public function __construct($code, $price, $currency = null, $count = 1)
    {
        $this->code = $code;
        $this->name = $code;
        $this->title = $code;
        $this->description = $code;
        $this->images = [];
        $this->origin_price = $price * 5;
        $this->price = $price;
        $this->currency = $currency;
        $this->count = $count;
    }
}