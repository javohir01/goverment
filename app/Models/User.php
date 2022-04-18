<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    use Notifiable;


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }

    public function district() {
        return $this->belongsTo('App\Models\District', 'district_id');
    }

    public function region() {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }
    public function roles() {
        return $this->belongsTo('App\Models\Roles', 'role_id');
    }
    public function isAdmin()
    {
        return $this->roles()->first()->name == 'admin';
    }
    public function isRegion()
    {
        return $this->roles()->where('id', '2')->exists();
    }

    public function isDistrict()
    {
        return $this->roles()->where('id', '3')->exists();
    }

}
