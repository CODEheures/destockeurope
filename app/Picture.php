<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picture extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'advert_id',
        'hashName',
        'path',
        'disk',
        'isThumb'
    ];
    protected $dates = ['deleted_at'];

    public function advert(){
        return $this->belongsTo('App\Advert');
    }
}
