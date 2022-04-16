<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table =  "statuses";


    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name'
    ];}
