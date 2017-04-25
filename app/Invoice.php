<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {
    use SoftDeletes;

    const PAYPAL = 0;
    const CARD_STRIPE = 1;

    const STATE_CREATION = 0;
    const STATE_RENEW = 1;

    protected $fillable = [
        'user_id',
        'advert_id',
        'invoice_number',
        'method',
        'authorization',
        'captureId',
        'voidId',
        'state',
        'options',
        'cost',
        'tva_customer',
        'tva_provider',
        'vatIdentifier'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    protected $appends = array('url', 'storagePath', 'filePath');

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function advert() {
        return $this->belongsTo('App\Advert');
    }

    public function getUrlAttribute() {
        return route('invoice.show', ['id' => $this->id]);
    }

    public function getStoragePathAttribute() {
        $storagePath = null;
        if(!is_null($this->invoice_number)){
            $year = Carbon::parse($this->created_at)->year;
            $month = Carbon::parse($this->created_at)->month;
            $storagePath = 'invoices/' . $year . '/' . $month ;
        }
        return $storagePath;
    }

    public function getFilePathAttribute() {
        $filePath = null;
        if(!is_null($this->getStoragePathAttribute())){
            $filePath = storage_path('app/'. $this->getStoragePathAttribute() . '/' .$this->id.'.pdf');
        }
        return $filePath;
    }

}
