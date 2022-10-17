<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = "invoice";
    protected $dates = ['month', 'due_date'];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function detail()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }
}
