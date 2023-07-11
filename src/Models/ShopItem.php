<?php

namespace Hanoivip\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopItem extends Model
{
    public $timestamps = true;
    
    public function getImagesAttribute($value)
    {
        return json_decode($value);
    }
    
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
