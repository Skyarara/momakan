<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Employee extends Model
{
    protected $table = "employee";
    protected $fillable = ['name', 'telp', 'email'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contract_employee(){
        return $this->hasMany(ContractEmployee::class, 'employee_id');
    }
}
