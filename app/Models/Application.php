<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    const NEW = 1;
    const CONFIRMED = 2;
    const REJECTED = 3;
    protected $table = 'applications';

    protected $fillable = ['id', 'status', 'number', 'phone_number', 'code', 'f_name', 'social_id', 'l_name', 'm_name', 'birth_date', 'region_id', 'district_id', 'address', 'passport', 'pin', 'remember_token', 'created_at', 'updated_at',];

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
    }}
