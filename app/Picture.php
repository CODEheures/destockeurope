<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picture extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hashName',
        'path',
        'disk',
        'isThumb',
        'advert_id'
    ];
    protected $dates = ['deleted_at'];

    public function advert(){
        return $this->belongsTo('App\Advert');
    }
}
