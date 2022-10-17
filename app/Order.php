<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";

    protected $dates = ['datetime'];

    public function employee() 
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employee_contract_employee()
    {
        return $this->belongsTo(ContractEmployee::class, 'employee_id', 'employee_id');
    }

    public function order_detail()
    {
        return $this->hasMany(OrderDetail::Class);
    }

    public function invoice_detail()
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function order_detail_extra()
    {
        return $this->hasMany(OrderDetail::Class)->where('isExtra', 1);
    }

    public function contractDetail()
    {
        return $this->belongsTo(ContractDetail::class,'contract_detail_id','id');
    }
}
