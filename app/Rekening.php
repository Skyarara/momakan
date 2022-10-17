<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model {
    protected $table ="rekening";
    protected $fillable = ['bank_logo','bank_name','account_name','account_number'];

    public function getRekeningById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id',$id);
        return $this->db->single();
    }
}
