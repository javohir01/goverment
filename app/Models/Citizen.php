<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    use HasFactory;

    const ADMIN = 1;
    const REGION = 2;
    const DISTRICT = 3;
    protected $table = 'citizens';

    protected $fillable = ['f_name', 'social_id', 'l_name', 'm_name', 'birth_date', 'region_id', 'district_id', 'address', 'passport', 'pin', 'remember_token', 'created_at', 'updated_at',];

    protected $guarded = ['id'];

    public static function rules()
    {
        return [
            'f_name' => 'string|nullable',
            'l_name' => 'string|nullable',
            'm_name' => 'string|nullable',
            'birth_date' => 'integer|nullable',
            'region_id' => 'integer|nullable',
            'district_id' => 'integer|nullable',
            'social_id' => 'integer|nullable',
            'address' => 'string|nullable',
            'password' => 'string|nullable',
            'passport' => 'string|nullable',
            'pin' => 'integer|nullable',
            'remember_token' => 'string|nullable',
            'created_at' => 'datetime|nullable',
            'updated_at' => 'datetime|nullable',

        ];
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District', 'district_id');
    }
    public function socialStatus()
    {
        return $this->belongsTo('App\Models\SocialStatus', 'social_id');
    }

    public function getBirthDateAttribute(){
        return date('d.m.Y', strtotime($this->attributes['birth_date']));
    }

    public function setBirthDateAttribute($value)
    {
        if (strpos($value, '.')) {
            $b_date = explode(".", $value);
            $value = $b_date[2] . "-" . $b_date[1] . "-" . $b_date[0];
        }
        $this->attributes['birth_date'] = $value;
    }

    public function scopeFilter(Builder $query, $attributes)
    {
        return $query->when($attributes['region_id'] ?? null, function (Builder $query, $region_id) {
            return $query->where('citizens.region_id', '=', $region_id);
        })->when($attributes['district_id'] ?? null, function (Builder $query, $district_id) {
            return $query->where('citizens.district_id', '=', $district_id);
        })->when($attributes['social_id'] ?? null, function (Builder $query, $social_id) {
            return $query->where('citizens.social_id', '=', $social_id);
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
