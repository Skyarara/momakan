<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeMenu extends Model
{
    protected $table = "employee_menu";

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    protected $fillable = ['menu_id'];
}
