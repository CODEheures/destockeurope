<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anonymous extends Model
{
    protected $fillable = [
        'name',
        'email',
        'compagnyName',
        'phone',
    ];
}
