<?php
namespace Hanoivip\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public function getUnlockAttribute($value)
    {
        return json_decode($value);
    }
}
