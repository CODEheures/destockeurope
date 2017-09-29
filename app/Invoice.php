<?php

namespace App;

use App\Common\MoneyUtils;
use App\Common\PrivilegesUtils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {
    use SoftDeletes;

    const PAYPAL = 0;
    const BRAINTREE = 1;

    const STATE_CREATION = 0;
    const STATE_RENEW = 1;
    const STATE_BACKTOTOP = 2;
    const STATE_HIGHLIGHT = 3;
    const STATE_EDIT = 4;

    protected $fillable = [
        'user_id',
        'advert_id',
        'invoice_number',
        'method',
        'authorization',
        'transaction_id',
        'voided',
        'captured',
        'refunded',
        'captureId',
        'voidId',
        'refundId',
        'state',
        'options',
        'cost',
        'tva_customer',
        'tva_provider',
        'vatIdentifier'
    ];

    protected $casts = [
        'options' => 'array',
        'voided' => 'Boolean',
        'captured' => 'Boolean',
        'refunded' => 'Boolean'
    ];

    protected $appends = array('url', 'refundUrl', 'storagePath', 'filePath', 'costWithDecimalAndCurrency', 'isUserOwner');

    public function user() {
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function advert() {
        return $this->belongsTo('App\Advert');
    }

    public function getUrlAttribute() {
        return route('admin.invoice.show', ['id' => $this->id]);
    }

    public function getIsUserOwnerAttribute() {
        if(auth()->check()){
            return auth()->user()->id === $this->user_id;
        }
        return false;
    }

    public function getRefundUrlAttribute() {
        if (PrivilegesUtils::canRefund()
            && !($this->refunded) && !($this->voided) && ($this->captured)
        ) {
            return route('advert.refund', ['id' => $this->id]);
        } else {
            return null;
        }
    }

    public function getCostWithDecimalAndCurrencyAttribute() {
        return MoneyUtils::getPriceWithDecimal($this->cost, "EUR");
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
