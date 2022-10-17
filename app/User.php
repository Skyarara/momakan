<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'phone_number', 'password', 'role_id', 'fcm_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function corporate() {
        return $this->hasOne(Corporate::class, 'user_id' ,'id');
    }

    public function vendor() {
        return $this->hasOne(Vendor::class, 'user_id' ,'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
