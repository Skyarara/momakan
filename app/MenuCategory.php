<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    protected $table = 'menu_category';

    public function menu() {
        return $this->hasMany(Menu::class, 'menu_category_id');
    }
}
