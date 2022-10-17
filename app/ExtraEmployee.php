<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraEmployee extends Model
{
    protected $table = "extra_employee_food";

    protected $fillable = ['employee_food_id','food_id','notes','qty'];
}
