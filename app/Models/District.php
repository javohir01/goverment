<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
//    public function region() {
//        return $this->belongsTo('App\Models\Region', 'region_id');
//    }
    public function citizens() {
        return $this->hasMany('App\Models\Citizen');
    }
}

