<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuPlan extends Model
{
    protected $table = 'menu_plan';

    protected $dates=[
        'date'
    ];

    public function detail()
    {
        return $this->hasMany(MenuPlanDetail::class);
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereMonth('date', $search);
        }
        return $query;
    }

    // public function getDateAttribute()
    // {
    // return \Carbon\Carbon::parse($this->attributes['date'])
    //    ->format('l, d F');
    // }
}
