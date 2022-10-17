<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $table = "contract";

    protected $fillable = ['id', 'budget_max_order', 'contract_code', 'corporate_id', 'date_start', 'date_end', 'initial_food_id'];

    public function corporate()
    {
        return $this->belongsTo(Corporate::class, 'corporate_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'contract_id', 'id');
    }

    public function contractDetail()
    {
        return $this->hasMany(ContractDetail::class,'contract_id');
    }

    public function employeInContract()
    {
        return $this->hasManyThrough(ContractEmployee::class,ContractDetail::class, 'contract_id','contract_detail_id');
    }

    // public function scopeFilter($query, $request)
    // {
    //     if ($request->has('active')) {
    //         $time = date('Y-m-d');
    //         $query->where('date_start', '<=', $time)->where('date_end', '>=', $time);
    //     }

    //     if ($request->has('inactive')) {
    //         $time = date('Y-m-d');
    //         $query->where('date_start', '>', $time)->orWhere('date_end', '<', $time);
    //     }
    // }
}
