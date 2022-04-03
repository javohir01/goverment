<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    const ADMIN = 1;
    const REGION = 2;
    const DISTRICT = 3;
    protected $table = 'citizens';

    protected $fillable = ['f_name', 'l_name', 'm_name', 'birth_date', 'region_id', 'district_id', 'address', 'passport', 'pin', 'remember_token', 'created_at', 'updated_at',];

    public static function rules()
    {
        return [
            'f_name' => 'string|nullable',
            'l_name' => 'string|nullable',
            'm_name' => 'string|nullable',
            'birth_date' => 'integer|nullable',
            'region_id' => 'integer|nullable',
            'district_id' => 'integer|nullable',
            'address' => 'string|nullable',
            'password' => 'string|nullable',
            'tin' => 'integer|nullable',
            'remember_token' => 'string|nullable',
            'created_at' => 'datetime|nullable',
            'updated_at' => 'datetime|nullable',

        ];
    }

//    public function region()
//    {
//        return $this->belongsTo(Region::class,'region_id','id');
//    }
//
//    public function district()
//    {
//        return $this->belongsTo('App\Models\District','district_id');
//    }
    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District', 'district_id');
    }

    public function scopeFilter(Builder $query, $attributes)
    {
        return $query->when($attributes['region_id'] ?? null, function (Builder $query, $region_id) {
            return $query->where('citizens.region_id', '=', $region_id);
        })->when($attributes['district_id'] ?? null, function (Builder $query, $district_id) {
            return $query->where('citizens.district_id', '=', $district_id);
        })->when($attributes['l_name'] ?? null, function (Builder $query, $l_name) {
            return $query->where('citizens.l_name', 'like', $l_name.'%');
        })->when($attributes['firstname'] ?? null, function (Builder $query, $firstname) {
            return $query->where('citizens.firstname', '=', $firstname);
        })->when($attributes['patronymic'] ?? null, function (Builder $query, $patronymic) {
            return $query->where('citizens.patronymic', '=', $patronymic);
        })->when($attributes['passport'] ?? null, function (Builder $query, $passport) {
            return $query->where('citizens.passport', '=', $passport);
        })->when($attributes['pin'] ?? null, function (Builder $query, $pin) {
            return $query->where('citizens.pin', '=', $pin);
        })->when($attributes['living_place'] ?? null, function (Builder $query, $living_place) {
            return $query->where('citizens.living_place', '=', $living_place);
        });
    }
}
