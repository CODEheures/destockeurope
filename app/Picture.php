<?php

namespace App;

use App\Common\PicturesManager;
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
    protected $appends = array('url');

    public function advert(){
        return $this->belongsTo('App\Advert');
    }

    public function getUrlAttribute() {
        if($this->isThumb){
            return route('picture.thumb', ['type' => PicturesManager::TYPE_FINAL_LOCAL, 'hashName' => $this->hashName, 'advertId' => $this->advert_id]);
        } else {
            return route('picture.normal', ['hashName' => $this->hashName, 'advertId' => $this->advert_id]);
        }
    }
}
