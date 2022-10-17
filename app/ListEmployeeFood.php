<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListEmployeeFood extends Model
{
    protected $table = "employee_menu";


    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    public function food() {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function contract(){
        return $this->belongsTo(Contract::class, 'id');
    }
}
