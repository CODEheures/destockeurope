<?php

namespace App\Common;

use App\Advert;
use App\Stats;

class StatsManager
{
    public function getStats(){
        //Nombre d'annonces en ligne
        $totalAdverts = Advert::where('isValid', '=', true)->count();
        $totalInvalidAdverts = Advert::invalid()->count();
        $totalWaitingAdverts = Advert::waiting()->count();



        $stat = Stats::create([
            'totalAdverts' => $totalAdverts,
            'totalInvalidAdverts' => $totalInvalidAdverts,
            'totalWaitingAdverts' => $totalWaitingAdverts,
            'totalNewViews' => 0,
            'totalNewFreeAdverts' => 0,
            'totalNewCostAdverts' => 0,
            'totalCosts' => 0
        ]);


        return $stat;
    }

}