<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookmark extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'advert_id',
    ];
    protected $dates = ['deleted_at'];
}
