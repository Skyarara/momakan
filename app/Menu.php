<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';


    protected $fillable = ['menu_id'];

    public function categories()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%");
        }
        return $query;
    }

    public function scopeKategori($query, $request)
    {
        if ($request->has('kategori')) {
            $search = $request->kategori;
            $query->where('menu_category_id', $search);
        }
        return $query;
    }

    public function getFormattedPriceAttribute()
    {
    return number_format($this->attributes['price'], 2);
    }
}
