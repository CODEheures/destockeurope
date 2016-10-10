<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetaCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['title'];
    protected $dates = ['deleted_at'];

    public function categories() {
        return $this->hasMany('App\Category');
    }
}
