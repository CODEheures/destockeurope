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
    protected $casts = [
        'isThumb' => 'Boolean',
    ];

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

    //local scopes
    public function scopeParents($query) {
        return $query->where('hashName' , '=', $this->hashName)
            ->where('path', '=', $this->path)
            ->where('disk', '=', $this->disk)
            ->where('isThumb', '=', $this->isThumb)
            ->withTrashed();
    }

    public function scopeFindByHashAndAdvertId($query, $hashName, $advertId) {
        return $query->withTrashed()->where('hashName', '=', $hashName)
            ->where('advert_id','=',$advertId);
    }

    public function scopeFindThumb($query, $hashName, $advertId) {
        return $query->findByHashAndAdvertId($hashName,$advertId)
            ->where('isThumb', '=', true);
    }

    public function scopeFindNormal($query, $hashName, $advertId) {
        return $query->findByHashAndAdvertId($hashName,$advertId)
            ->where('isThumb', '=', false);
    }

    public function scopeOnLocalDisk($query) {
        return $query->where('disk', '=', 'local');
    }

    public function scopeFindByAdvertIdWithTrashed($query, $advertId) {
        return $query->withTrashed()->where('advert_id','=',$advertId);
    }
}
