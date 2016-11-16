<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Common extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'masterAds',
        'urlMasterAds',
        'offsetYMasterAds',
        'adsFrequency',
        'advertsPerPage',
        'urgentCost',
        'nbFreePictures',
        'nbMaxPictures'
    ];
}