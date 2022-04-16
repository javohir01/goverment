<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    const ADMIN = 1;
    const REGION_GOVERNMENT = 2;
    const DISTRICT_GOVERNMENT = 3;
    const NEW = 1;
    const CONFIRMED = 2;
    const REJECTED = 3;
    protected $table = 'applications';

    protected $fillable = ['id','deny_reason_id', 'status', 'number', 'phone_number', 'code', 'f_name', 'social_id', 'l_name', 'm_name', 'birth_date', 'region_id', 'district_id', 'address', 'passport', 'pin', 'remember_token', 'created_at', 'updated_at',];

    protected $guarded = ['id'];

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
    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status');
    }
    public function denyReason()
    {
        return $this->belongsTo('App\Models\DenyReason', 'deny_reason_id');
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
