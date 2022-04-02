<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    const ADMIN = 1;
    const REGION = 2;
    const CITY = 3;
    protected $table = 'citizens';

    protected $fillable = ['first_name', 'last_name', 'fathers_name', 'birth_date', 'region_id', 'city_id', 'address', 'password', 'passport', 'tin', 'remember_token', 'created_at', 'updated_at',];

    public static function rules()
    {
        return [
            'first_name' => 'string|nullable',
            'last_name' => 'string|nullable',
            'fathers_name' => 'string|nullable',
            'birth_date' => 'integer|nullable',
            'region_id' => 'integer|nullable',
            'city_id' => 'integer|nullable',
            'address' => 'string|nullable',
            'password' => 'string|nullable',
            'passport' => 'string|nullable',
            'tin' => 'integer|nullable',
            'remember_token' => 'string|nullable',
            'created_at' => 'datetime|nullable',
            'updated_at' => 'datetime|nullable',

        ];
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region','region_id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District','district_id');
    }
}
