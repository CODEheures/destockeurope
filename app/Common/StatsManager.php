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


        $stat = Stats::create([
            'totalAdverts' => $totalAdverts,
            'totalInvalidAdverts' => $totalInvalidAdverts,
            'totalWaitingAdverts' => $totalWaitingAdverts,
            'totalNewViews' => 0,
            'totalNewFreeAdverts' => 0,
            'totalNewCostAdverts' => 0,
            'totalCosts' => 0,
            'countLocalFiles' => $infoLocalFiles[0],
            'sizeLocalFiles'  => round($infoLocalFiles[1]/(1024*1024),0),
            'countDistantFiles'  => $infoDistantFiles[0],
            'sizeDistantFiles'  => round($infoDistantFiles[1]/(1024*1024),0),
        ]);


        return $stat;
    }

}