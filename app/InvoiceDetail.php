<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoice_detail';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    
}
