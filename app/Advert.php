<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advert extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'user_id', 'category_id', 'type', 'title', 'description', 'price'];
    protected $dates = ['deleted_at'];


}
