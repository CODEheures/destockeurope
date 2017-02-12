<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parameters extends Model
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
        'urlLinkMasterAds',
        'offsetYMasterAds',
        'adsFrequency',
        'advertsPerPage',
        'urgentCost',
        'videoCost',
        'renewCost',
        'nbFreePictures',
        'nbMaxPictures',
        'welcomeType',
        'advertResumeLenght',
        'maxNumberOfSearchResults',
        'minLengthSearch'
    ];
}