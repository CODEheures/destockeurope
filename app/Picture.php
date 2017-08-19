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
        'isThumb',
        'thumbUrl',
        'normalUrl'
    ];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'isThumb' => 'Boolean',
    ];

    public function advert(){
        return $this->belongsTo('App\Advert');
    }


    //local scopes
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

    public function scopeWithUrl($query, $url) {
        return $query->withTrashed()->where('thumbUrl', $url)->orWhere('normalUrl', $url);
    }
}
