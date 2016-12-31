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

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function advert() {
        return $this->belongsTo('App\Advert');
    }

    //local scopes
    public function scopeExistForAuthUser($query, $advertId) {
        return $query->where('advert_id', $advertId)->where('user_id', auth()->user()->id);
    }

    public function scopeMines($query) {
        return $query->where('user_id', auth()->user()->id);
    }
}
