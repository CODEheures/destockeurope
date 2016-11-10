<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model {
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'reference'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function advert() {
        return $this->belongsTo('App\Advert');
    }

}
