<?php

namespace App\Console\Commands;

use App\Common;
use App\Common\PicturesManager;
use App\Common\StatsManager;
use App\Picture;
use Illuminate\Console\Command;

class TransfertMedias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medias:transfert {quantity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfert medias to cloud';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commons = Common::latest()->first();
        if($commons && !$commons->isOnTransfert){
            $total = $this->argument('quantity');
            $partial = 0;
            $commons->isOnTransfert = true;
            $commons->transfertPartial = $partial;
            $commons->transfertTotal = $total;
            $commons->save();
            //move pictures to distant disk
            $pictureManager = new PicturesManager();
            $pictures = Picture::where('disk', '=', 'local')->take($total)->get();
            foreach ($pictures as $picture){
                $pictureManager->moveToDistantFinal($picture);
                $partial++;
                $commons->transfertPartial = $partial;
                $commons->save();
                $this->info($partial . ' fichier(s) sur ' . $total . ' copié(s)');
            }
            //update stats
            $statsManager = new StatsManager();
            $statsManager->getStats();
            $commons->isOnTransfert = false;
            $commons->save();
        } else {
            $this->info('Abort: transfert already exist');
        }
        return null;
    }
}
