<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table =  "roles";


    public $primaryKey = 'id';

    const ADMIN = 1;
    const REGION = 2;
    const DISTRICT = 3;

    public $timestamps = false;

    protected $fillable = ['user_id', 'role'];

    public static function rules()
    {
        return [
            'user_id' => 'integer|nullable',
            'role' => 'string|nullable',
        ];
    }
}
