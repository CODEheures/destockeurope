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
