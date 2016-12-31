<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    //local scopes
    public function scopeViewsByDay($query, $date) {
        return $query->select(array(DB::raw('DATE(`created_at`) as `date`'), DB::raw('SUM(totalNewViews) as views')))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC');
    }

    public function scopeAdvertsByDay($query, $date) {
        return $query->select(array(
            DB::raw('DATE(`created_at`) as `date`'),
            DB::raw('AVG(totalAdverts) as valid_adverts'),
            DB::raw('AVG(totalInvalidAdverts) as invalid_adverts'),
            DB::raw('AVG(totalWaitingAdverts) as waiting_adverts'),
            DB::raw('SUM(totalNewFreeAdverts) as new_free_adverts'),
            DB::raw('SUM(totalNewCostAdverts) as new_cost_adverts'),
        ))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC');
    }

    public function scopeCostsByDay($query, $date) {
        return $query->select(array(
            DB::raw('DATE(`created_at`) as `date`'),
            DB::raw('(SUM(totalCosts)/100) as sum_costs'),
            DB::raw('((SUM(totalCosts)/SUM(totalNewCostAdverts))/100) as avg_costs')
        ))
            ->where('created_at', '>', $date)
            ->groupBy('date')
            ->orderBy('date', 'ASC');
    }
}
