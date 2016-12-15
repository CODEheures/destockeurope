<?php

namespace App\Common;

use App\Advert;
use App\Stats;

class StatsManager
{
    public function getStats(){
        //Nombre d'annonces en ligne
        $totalAdverts = Advert::where('isValid', '=', true)->count();
        $totalInvalidAdverts = Advert::where('isValid', '=', false)->count();
        $totalWaitingAdverts = Advert::where('isValid', '=', null)->count();
        $totalCostAdverts = Advert::where('cost', '>', 0)->count();
        $totalAverageCost = Advert::where('cost', '>', 0)->avg('cost') || 0;
        $totalNewView = 0;
        //Nombre de fichiers et taille total
        $pictureManager = new PicturesManager();
        $infoLocalFiles=[0,0];
        $infoDistantFiles=[0,0];
        try {
            $infoLocalFiles = $pictureManager->infoLocalFiles();
        } catch (\Exception $e) {
            null;
        }
        try {
            $infoDistantFiles = $pictureManager->infoDistantFiles();
        } catch (\Exception $e) {
            null;
        }


        Stats::create([
            'totalAdverts' => $totalAdverts,
            'totalInvalidAdverts' => $totalInvalidAdverts,
            'totalWaitingAdverts' => $totalWaitingAdverts,
            'totalCostAdverts' => $totalCostAdverts,
            'totalAverageCost' => $totalAverageCost,
            'totalNewView' => $totalNewView,
            'countLocalFiles' => $infoLocalFiles[0],
            'sizeLocalFiles'  => round($infoLocalFiles[1]/(1024*1024),0),
            'countDistantFiles'  => $infoDistantFiles[0],
            'sizeDistantFiles'  => round($infoDistantFiles[1]/(1024*1024),0),
        ]);


        return null;
    }

}