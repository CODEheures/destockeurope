<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {
    use SoftDeletes;

    const PAYPAL = 0;
    const CARD_STRIPE = 1;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'method',
        'authorization',
        'captureId',
        'voidId'
    ];



    public function user() {
        return $this->belongsTo('App\User');
    }

    public function advert() {
        return $this->hasOne('App\Advert');
    }

}
