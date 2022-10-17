<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = "feedback";

    protected $guarded = [''];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // public function order() 
    // {
    //     return $this->belongsTo(Order::class, 'order_id');
    // }

}
