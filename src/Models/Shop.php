<?php
namespace Hanoivip\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    public $timestamps = true;
    
    public function getUnlockAttribute($value)
    {
        return json_decode($value);
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(ShopItem::class);
    }
}
