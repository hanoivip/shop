<?php

namespace Hanoivip\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    public $timestamps = true;
    
    public function getCartAttribute($value)
    {
        return json_decode($value);
    }
}
