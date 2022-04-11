<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneCode extends Model
{
    protected $table = 'phone_codes';

    protected $fillable = ['phone_number', 'code'];

    protected $guarded = ['id'];
}
