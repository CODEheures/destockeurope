<?php

namespace App\Jobs;

use App\Common;
use App\Common\PicturesManager;
use App\Common\StatsManager;
use App\Picture;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransferMedias implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $quantity;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $commons = Common::latest()->first();
        if($commons && !$commons->isOnTransfert){
            $total = $this->quantity*1024; //kb
            $partial = 0;
            $sizeBeforeMove = 0;
            $commons->isOnTransfert = true;
            $commons->transfertPartial = $partial;
            $commons->transfertTotal = $total;
            $commons->save();
            //move pictures to distant disk
            $pictureManager = new PicturesManager();
            $picture = Picture::onLocalDisk()->first();
            while ($picture && $partial < $total){
                $sizeBeforeMove = $pictureManager->getSize($picture);
                if($sizeBeforeMove != 0){
                    $pictureManager->moveToDistantFinal($picture);
                    $partial += round($sizeBeforeMove/1024,0);
                    $picture = Picture::onLocalDisk()->first();
                    $commons->transfertPartial = $partial;
                    $commons->save();
                } else {
                    break;
                }
            }
            //update stats
            $statsManager = new StatsManager();
            $statsManager->getStats();
            $commons->isOnTransfert = false;
            $commons->save();
        }
        return null;
    }
}
