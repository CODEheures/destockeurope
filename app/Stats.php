<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $fillable = [
        'totalAdverts',
        'totalInvalidAdverts',
        'totalWaitingAdverts',
        'totalCostAdverts',
        'totalAverageCost',
        'totalNewView',
        'countLocalFiles',
        'sizeLocalFiles',
        'countDistantFiles',
        'sizeDistantFiles'
    ];
}
