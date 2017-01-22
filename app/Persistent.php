<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persistent extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

}
