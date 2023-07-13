<?php
namespace Hanoivip\Shop\ViewObjects;

use Illuminate\Support\Str;

class ShopVO
{
    public $name;
    
    public $slug;
    
    public $conditions;
    
    public function __construct($name)
    {
        $this->name = $name;
        $this->slug = Str::slug($name);
        $this->conditions = [];
    }
}