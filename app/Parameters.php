<?php

namespace App;

use Carbon\Carbon;
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
        'photoCost',
        'urgentCost',
        'backToTopCost',
        'highlightCost',
        'videoCost',
        'renewCost',
        'editCost',
        'validityCosts',
        'nbFreePictures',
        'nbMaxPictures',
        'welcomeType',
        'advertResumeLenght',
        'maxNumberOfSearchResults',
        'minLengthSearch'
    ];

    protected $casts = [
        'masterAds' => 'Boolean',
    ];
}
