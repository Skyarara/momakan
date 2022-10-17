<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractDetail extends Model
{
    protected $table = "contract_detail";

    public function contract()
    {
        return $this->BelongsTo(Contract::class, 'contract_id');
    }

    public function contract_employee()
    {
        return $this->hasMany(ContractEmployee::class, 'contract_detail_id');
    }

    public function order_employee()
    {
        return $this->hasMany(Order::class,'contract_detail_id');
    }
}
