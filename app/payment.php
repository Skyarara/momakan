<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $table = 'payment';

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
