<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuPlanDetail extends Model
{
    protected $table = 'menu_plan_detail';

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
