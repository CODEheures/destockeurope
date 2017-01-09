<?php

use App\Advert;
use App\Picture;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;

class AdvertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statsManager = new \App\Common\StatsManager();

        $user2 = \App\User::where('email', '=', 'vendeur@d.e')->first();
        $user4 = \App\User::where('email', '=', 'delegation@d.e')->first();

        $subCategory212 = \App\Category::where('description', '=', '{"fr":"homme","en":"men"}')->first();
        $subCategory213 = \App\Category::where('description', '=', '{"fr":"bi","en":"bi"}')->first();
        $subCategory12 = \App\Category::where('description', '=', '{"fr":"electroniques","en":"electronics"}')->first();
        $subCategory11 = \App\Category::where('description', '=', '{"fr":"crayons","en":"pens"}')->first();
        $subCategory22 = \App\Category::where('description', '=', '{"fr":"Sacs","en":"Bags"}')->first();

        $this->advertCreate(
            $user4->id,
            $subCategory212->id,
            Carbon::now()->subDays(5),
            0,
            '1000 doudounes en fin de série à prix cassé',
            ['11111111111111111111111111111111'],
            1000,
            50,
            0,
            'Cette annonce est une annonce de test de délégation de vente',
            true,
            57.35
        );
        $advert = Advert::first();
        $advert->is_delegation=true;
        $advert->save();
        $advert=null;

        $this->advertCreate(
            $user2->id,
            $subCategory213->id,
            Carbon::now()->subDays(5),
            0,
            'Un lot de 28 chiens de cirque',
            ['22222222222222222222222222222222', '33333333333333333333333333333333', '44444444444444444444444444444444'],
            28,
            8,
            1,
            'Ceci est une annonce à valider, ... ou pas!!??. Je vends un lot de 28 chiens de cirque. Il savent faire du trapeze, de la moto et font aussi la vaisselle.',
            true
        );

        $stat1 = $statsManager->getStats();
        $stat1->created_at = Carbon::now()->subDays(5);
        $stat1->totalNewViews = 328;
        $stat1->totalNewFreeAdverts = 56;
        $stat1->totalNewCostAdverts = 14;
        $stat1->totalCosts = 42800;
        $stat1->save();


        // J-4
        $this->advertCreate(
            $user2->id,
            $subCategory12->id,
            Carbon::now()->subDays(4),
            6900,
            'Excellent état! 10 Cartons de casques AudioBeats',
            ['55555555555555555555555555555555'],
            10,
            2,
            2
        );

        $this->advertCreate(
            $user2->id,
            $subCategory12->id,
            Carbon::now()->subDays(4),
            2000,
            '10000 lunettes Gears pour samsung S7...\':(',
            ['66666666666666666666666666666666'],
            10000,
            500,
            3
        );

        $this->advertCreate(
            $user2->id,
            $subCategory11->id,
            Carbon::now()->subDays(4),
            0,
            '3 palettes de boites à dessin avec defauts',
            ['77777777777777777777777777777777'],
            3,
            1,
            0
        );

        $stat2 = $statsManager->getStats();
        $stat2->created_at = Carbon::now()->subDays(4);
        $stat2->totalNewViews = 211;
        $stat2->totalNewFreeAdverts = 21;
        $stat2->totalNewCostAdverts = 0;
        $stat2->totalCosts = 0;
        $stat2->save();


        // J-2
        $this->advertCreate(
            $user2->id,
            $subCategory22->id,
            Carbon::now()->subDays(2),
            1000,
            '500 sacs à dos EastPack Gris à saisir',
            ['88888888888888888888888888888888'],
            500,
            30,
            0
        );

        $stat3 = $statsManager->getStats();
        $stat3->created_at = Carbon::now()->subDays(2);
        $stat3->totalNewViews = 404;
        $stat3->totalNewFreeAdverts = 56;
        $stat3->totalNewCostAdverts = 14;
        $stat3->totalCosts = 42800;
        $stat3->save();



        //J - 1
        $this->advertCreate(
            $user2->id,
            $subCategory11->id,
            Carbon::now()->subDays(1),
            4900,
            '42 kilos de crayons tout venant',
            ['99999999999999999999999999999999'],
            42,
            5,
            1
        );

        $this->advertCreate(
            $user2->id,
            $subCategory12->id,
            Carbon::now()->subDays(1),
            1234,
            '200 clés USB suite à depot de bilan',
            ['00000000000000000000000000000000'],
            200,
            50,
            4
        );


        $stat4 = $statsManager->getStats();
        $stat4->created_at = Carbon::now()->subDays(1);
        $stat4->totalNewViews = 528;
        $stat4->totalNewFreeAdverts = 87;
        $stat4->totalNewCostAdverts = 25;
        $stat4->totalCosts = 62100;
        $stat4->save();
    }

    private function advertCreate($userId, $catId, $created_at, $cost, $title, Array $pictures, $maxQuantity, $lotMini, $location, $description=null, $setNullValid=null, $price=null){
        $advert = new Advert();
        $advert->user_id = $userId;
        $advert->category_id = $catId;
        $advert->created_at = $created_at;
        $advert->cost = $cost;
        $advert->type = 'bid';
        $advert->title = $title;
        if($description){
            $advert->description = $description;
        } else {
            $advert->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus ullamcorper lectus, in semper dolor blandit id. Vestibulum quis orci convallis, elementum turpis a, scelerisque eros. Etiam dapibus sem libero, eu cursus elit aliquam facilisis. Nunc rutrum nulla purus, venenatis lobortis magna iaculis quis. Curabitur condimentum, lacus id ullamcorper pharetra, enim lectus hendrerit orci, sagittis cursus urna augue vel metus. Nunc egestas commodo felis, eu suscipit tortor. Duis at mauris ullamcorper, dapibus enim ut, imperdiet leo. Vestibulum mollis at mi quis vulputate. Donec eleifend, purus in sollicitudin sagittis, nibh dolor mattis nulla, sed condimentum lacus arcu at sapien. Donec ac turpis sit amet purus facilisis rutrum sit amet quis lacus. Pellentesque pharetra interdum turpis, sed varius ipsum scelerisque sed. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin augue magna, blandit molestie pharetra eget, sagittis quis tellus. Nullam quis molestie lorem. Cras at ornare diam, ut vulputate enim. Etiam enim mauris, auctor id pharetra nec, feugiat non purus.
Donec iaculis tellus eget ante sodales, vestibulum efficitur odio faucibus. Suspendisse non orci vitae quam rutrum pulvinar sit amet rhoncus metus. Suspendisse et ornare dui. Nullam mattis dui et nunc faucibus, ac rhoncus nibh consequat. Nullam arcu nisl, ultrices vel venenatis vitae, vehicula at odio. Phasellus suscipit viverra turpis id bibendum. Ut vestibulum venenatis ante eget ultrices. Nam et erat at tellus molestie scelerisque. Fusce in interdum dolor. Cras porta egestas lectus quis aliquet. Etiam quis arcu nisi. Ut pharetra non dui sit amet congue. Vivamus et ligula magna. Nulla malesuada, nunc id pulvinar pellentesque, tellus nisi congue est, id condimentum tellus tortor nec nisi. Aenean pretium fermentum tortor, eget auctor arcu tempor ac. Proin nibh tellus, scelerisque nec sagittis ut, posuere vitae enim. Cras non ligula efficitur, porta odio posuere.';
        }
        $advert->latitude = 47.3526;
        $advert->longitude = 0.6704089179992252;

        if($location == 0){
            $advert->locality = 'Joué-lès-Tours';
            $advert->postal_code = '37300';
            $advert->administrative_area_level_2 = 'Indre-et-Loire';
            $advert->administrative_area_level_1 = 'Centre-Val de Loire';
            $advert->country = 'FR';
            $advert->geoloc = 'Joué-lès-Tours, France';
            $advert->currency = 'EUR';
        } elseif ($location == 1) {
            $advert->locality = 'Chambray-lès-Tours';
            $advert->postal_code = '37170';
            $advert->administrative_area_level_2 = 'Indre-et-Loire';
            $advert->administrative_area_level_1 = 'Centre-Val de Loire';
            $advert->country = 'FR';
            $advert->geoloc = 'Chambray-lès-Tours, France';
            $advert->currency = 'EUR';
        } elseif ($location == 2) {
            $advert->locality = 'Montfort-sur-Meu';
            $advert->postal_code = '35160';
            $advert->administrative_area_level_2 = 'Ille-et-Vilaine';
            $advert->administrative_area_level_1 = 'Bretagne';
            $advert->country = 'FR';
            $advert->geoloc = 'Montfort-sur-Meu, France';
            $advert->currency = 'EUR';
        } elseif ($location == 3) {
            $advert->locality = 'San José';
            $advert->postal_code = null;
            $advert->administrative_area_level_2 = 'Comté de Santa Clara';
            $advert->administrative_area_level_1 = 'CA';
            $advert->country = 'US';
            $advert->geoloc = 'San José, Californie, États-Unis';
            $advert->currency = 'USD';
        } elseif ($location == 4) {
            $advert->locality = 'San José';
            $advert->postal_code = null;
            $advert->administrative_area_level_2 = null;
            $advert->administrative_area_level_1 = 'San José';
            $advert->country = 'CR';
            $advert->geoloc = 'San José, Costa Rica';
            $advert->currency = 'CRC';
        }

        $advert->mainPicture = $pictures[0];


        $advert->totalQuantity=$maxQuantity;
        $advert->lotMiniQuantity=$lotMini;
        $advert->isUrgent= filter_var(rand(0,1), FILTER_VALIDATE_BOOLEAN);

        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);

        $unitPrice = is_null($price) ? rand(1,20000)/100 : $price;

        $advert->price = $moneyParser->parse(strval($unitPrice),"EUR")->getAmount();

        $advert->isPublish = true;

        if(is_null($setNullValid)) {
            $loterie = rand(0,1);
            if($loterie==1){
                $advert->isValid = true;
            }
        } else {
            $advert->isValid = null;
        }

        if($advert->isValid) {
            $advert->online_at = $created_at;
        }

        DB::beginTransaction();
        $advert->save();

        foreach ($pictures as $item){
            $picture = new Picture();
            $picture->hashName = $item;
            $picture->path = '/final/1/';
            $picture->disk = 'local';
            $picture->isThumb = false;
            $advert->pictures()->save($picture);
            $picture->save();

            $picture = new Picture();
            $picture->hashName = $item;
            $picture->path = '/final/1/';
            $picture->disk = 'local';
            $picture->isThumb = true;
            $advert->pictures()->save($picture);
            $picture->save();
        }

        DB::commit();
    }

}
