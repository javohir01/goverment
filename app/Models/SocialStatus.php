<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialStatus extends Model
{
    protected $table =  "social_statuses";


    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name'
    ];}
