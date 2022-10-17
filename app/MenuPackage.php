<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuPackage extends Model
{
    protected $table = 'menu_package';

    protected $fillable = ['parent_id', 'menu_id'];

    protected $primaryKey = 'parent_id';

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function paket()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
