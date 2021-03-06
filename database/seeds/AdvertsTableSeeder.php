<?php

use App\Advert;
use App\Common\StatsManager;
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
        $statsManager = new StatsManager();

        $user2 = \App\User::where('email', '=', 'vendeur@destockeurope.com')->first();
        $user4 = \App\User::where('email', '=', 'fournisseur@destockeurope.com')->first();

        $cat_bagagerie = \App\Category::where('description', '=', '{"fr":"Bagagerie & sacs","en":"Luggage & Bags"}')->first();
        $cat_image_sons = \App\Category::where('description', '=', '{"fr":"Image & son","en":"Picture & sound"}')->first();
        $cat_montres = \App\Category::where('description', '=', '{"fr":"Montres & horloges","en":"Watches & clocks"}')->first();
        $cat_loisirs_autres = \App\Category::where('description', '=', '{"fr":"Autres loisirs","en":"Other hobbies"}')->first();
        $cat_info_composants = \App\Category::where('description', '=', '{"fr":"Composants Informatiques & Logiciels","en":"Computer Components & Software"}')->first();

        $this->advertCreate(
            $user4->id,
            $cat_bagagerie->id,
            Carbon::now()->subDays(5),
            0,
            '1000 supers sacs à dos antivol à saisir',
            ['2e913bc87151d291b0277b9786265ee3'],
            1000,
            10,
            0,
            'Super sac à dos antivol à prix cassé !' . PHP_EOL . 'A l’extérieur, il a beaucoup de poches cachées pour le transport urbain.' . PHP_EOL . 'A l’intérieur, des compartiments rembourrés pour ordinateur portable.',
            null,
            35.00,
            false,
            null
        );
        $advert = Advert::first();
        $advert->is_delegation=true;
        $advert->save();
        $advert=null;

        $this->advertCreate(
            $user2->id,
            $cat_image_sons->id,
            Carbon::now()->subDays(5),
            0,
            'Lot de 700 enceintes bluetooth',
            ['dc4c78ee3f1417e577a3dcde1cd7edf3', '73f4e847835194b26dc991db960245fa', '40926852a25c371fb968fc8954b52665'],
            700,
            50,
            1,
            'Super lot de 700 enceintes bluetooth avec motif géométrique',
            null,
            5.00,
            false,
            "BLT-125-FE3"
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
            $cat_montres->id,
            Carbon::now()->subDays(4),
            6900,
            'MONTRES LEXON "MOON"',
            ['862410caeed9a96ba19060c8ed7a725f'],
            1000,
            10,
            2,
            'Lot de 1000 montres "MOON" de la marque LEXON.' . PHP_EOL . 'Résistant à l\'eau.' . PHP_EOL . 'Mouvement SEIKO.',
            null,
            10.00,
            true,
            "LM2424"
        );

        $this->advertCreate(
            $user2->id,
            $cat_image_sons->id,
            Carbon::now()->subDays(4),
            2000,
            'Lot de Mini Radio "DOLMEN" LEXON',
            ['c1c528fdad42239b82a57bee7410081d', '90144aa33c3408905bcab83a4ba7fedf'],
            8000,
            100,
            0,
            'Superbe lot de 8000 mini radio "DOLMEN" de la marque LEXON' . PHP_EOL . 'Disponible en plusieurs couleurs.',
            null,
            8.00,
            false,
            null
        );

        $this->advertCreate(
            $user2->id,
            $cat_image_sons->id,
            Carbon::now()->subDays(4),
            0,
            '250 Gear VR',
            ['66666666666666666666666666666666'],
            250,
            50,
            0,
            'Destockage de 250 GEAR VR.'. PHP_EOL .'Très haute qualité, zero defaut et garanties 1 an.',
            null,
            119.95,
            false,
            "VR-A5632"
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
            $cat_bagagerie->id,
            Carbon::now()->subDays(2),
            1000,
            '500 sacs à dos EastPack Gris à saisir',
            ['88888888888888888888888888888888'],
            500,
            30,
            3,
            'A saisir rapidement, 500 sacs à dos de marque EastPack.' . PHP_EOL . 'TOP QUALITY. Envoi rapide.',
            null,
            57.65,
            false,
            null
        );

        $stat3 = $statsManager->getStats();
        $stat3->created_at = Carbon::now()->subDays(2);
        $stat3->totalNewViews = 404;
        $stat3->totalNewFreeAdverts = 56;
        $stat3->totalNewCostAdverts = 14;
        $stat3->totalCosts = 42800;
        $stat3->save();



        //J - 1
        for($i=1; $i<=config('runtime.tempo.seeder.quantity'); $i++) {

            $this->advertCreate(
                $user2->id,
                $cat_loisirs_autres->id,
                Carbon::now()->subDays(1),
                4900,
                '42 kilos de crayons tout venant',
                ['99999999999999999999999999999999'],
                42,
                5,
                4,
                'Ces crayons neuf sont vendus au kilo sans possibilité de choisir.' . PHP_EOL . 'Prix imbattable',
                true,
                1527.50,
                false,
                null
            );

            $this->advertCreate(
                $user2->id,
                $cat_info_composants->id,
                Carbon::now()->subDays(1),
                1234,
                '200 clés USB3 suite à depot de bilan',
                ['00000000000000000000000000000000'],
                200,
                50,
                0,
                'Exceptionnel: ' . PHP_EOL . 'Clés très haute capacités de 128Gb USB3.0' . PHP_EOL . 'Matériel de qualité sui vous assurera une revente dans des délai courts',
                true,
                18.95,
                false,
                "HCU3-128"
            );

            $this->advertCreate(
                $user2->id,
                $cat_image_sons->id,
                Carbon::now()->subDays(1),
                0,
                'Lunettes Réalité Virtuelle pliables.',
                ['e6274b78ea4710d8bc6c597997ab3466', 'd1bf34a836f0e09067b15d581659f771', '9231afb06cb5197625602e181ff4a44d'],
                1500,
                50,
                0,
                'Lunettes Réalité Virtuelle pliables. '
                . PHP_EOL . PHP_EOL . 'Ces lunettes de réalité Virtuelle en silicone sont idéales quand vous êtes en déplacement. Elles sont légères, pliables et assez réduites pour être glissées dans une poche. Glissez votre téléphone dans la fente et lancez une application VR pour plonger dans le monde de la réalité virtuelle en quelques secondes.'
                . PHP_EOL . PHP_EOL . 'Pour apprécier des vidéos 3D, des jeux VR et encore plus! Convient à la plupart des smartphones.'
                . PHP_EOL . PHP_EOL . 'Fourni sous écrin cadeau. Plastique ABS et silicone. 116.',
                true,
                3.64,
                false,
                null
            );

            $this->advertCreate(
                $user2->id,
                $cat_bagagerie->id,
                Carbon::now()->subDays(1),
                0,
                'Housse à vêtements Rollor®',
                ['bca2c9301a0cebc344f7f5149a14c036', '9c7d1bbc5aa4f85cfe2d6b4595b54b66', '8da67b8454d9e01b562d0ba5d9882721'],
                2420,
                50,
                0,
                'Housse à vêtements Rollor® avec système anti-froissage. La technologie anti-froissage rollology® permet de compacter les vêtements sans risque de froissage.',
                true,
                59.00,
                false,
                null
            );
        }


        $stat4 = $statsManager->getStats();
        $stat4->created_at = Carbon::now()->subDays(1);
        $stat4->totalNewViews = 528;
        $stat4->totalNewFreeAdverts = 87;
        $stat4->totalNewCostAdverts = 25;
        $stat4->totalCosts = 62100;
        $stat4->save();
    }

    private function advertCreate($userId, $catId, $created_at, $cost, $title, Array $pictures, $maxQuantity, $lotMini, $location, $description=null, $setNullValid=null, $price=null, $isNegociated=false, $manu_ref=null){
        $advert = new Advert();
        $advert->user_id = $userId;
        $advert->category_id = $catId;
        $advert->created_at = $created_at;
        $advert->type = 'bid';
        $advert->title = $title;
        $advert->manu_ref = $manu_ref;
        $advert->isNegociated = $isNegociated;
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

        $advert->isValid = $setNullValid;
        if($advert->isValid) {
            $advert->online_at = $created_at;
            $advert->setEndedAt();
        }

        DB::beginTransaction();
        $advert->save();

        foreach ($pictures as $item){
            $picture = new Picture();
            $picture->hashName = $item;
            $picture->thumbUrl = config('pictures.service.domains')[0] . '/600x600/1/' . $picture->hashName . '/jpg';
            $picture->normalUrl = config('pictures.service.domains')[0] . '/1200x675/1/' . $picture->hashName . '/jpg';
            $advert->pictures()->save($picture);
            $picture->save();
        }

        DB::commit();
    }

}
