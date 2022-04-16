<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DenyReason extends Model
{
    protected $table =  "deny_reasons";


    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name'
    ];}
