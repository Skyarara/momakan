<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractEmployee extends Model
{
    protected $table = "contract_employee";

    protected $fillable = ['isActive'];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
