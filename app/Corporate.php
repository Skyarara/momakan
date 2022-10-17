<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corporate extends Model
{
    protected $table ="corporate";
    protected $fillable = ['name','address','telp','phone_number'];

    public function getCoporateById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id',$id);
        return $this->db->single();
    }

    public function employee()
    {
        return $this->hasMany(Employee::class, 'corporate_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
