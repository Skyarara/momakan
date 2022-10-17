<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = "order_detail";

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getSubTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
