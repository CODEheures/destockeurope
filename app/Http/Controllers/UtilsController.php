<?php

namespace App\Http\Controllers;


use App\Advert;
use App\Category;
use App\Common;
use App\Picture;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Symfony\Component\HttpFoundation\Request;
use App\Common\LocaleUtils;
use App\Common\MoneyUtils;
use Vinkla\Vimeo\VimeoManager;

class UtilsController extends Controller
{

    use LocaleUtils;
    use MoneyUtils;

    private $vimeoManager;

    public function __construct(VimeoManager $vimeoManager) {
        $this->middleware('auth', ['only' => ['getListCurrencies', 'getListLocales', 'getListCardsType']]);
        $this->middleware('isAdminUser', ['only' => ['isPicture', 'tempo']]);
        $this->middleware('appOnDevelMode', ['only' => ['testGame','tempo']]);
        $this->vimeoManager = $vimeoManager;
    }


    public function getListCurrencies()  {
        return response()->json($this->listUserCurrencies());
    }

    public function getListLocales() {
        return response()->json($this->listUserLocales());
    }

    public function testGame(){
        Artisan::call('migrate:refresh');

        $testFiles = Storage::disk('local')->files('/testGame');
        foreach ($testFiles as $file){
            if(!Storage::disk('local')->exists(Common\PicturesManager::FINAL_LOCAL_PATH.'/1/'.basename($file))){
                Storage::disk('local')->copy($file, Common\PicturesManager::FINAL_LOCAL_PATH.'/1/'.basename($file));
            }
        }

        $parameters = new Common();
        $parameters->save();

        $statsManager = new Common\StatsManager();

        $lat = 47.3526;
        $lng = 0.6702587142943912;


        $user1 = new User();
        $user1->name = 'client';
        $user1->email = 'client@d.e';
        $user1->phone = '06.87.34.06.83';
        $user1->password = bcrypt('123456');
        $user1->locale = env('DEFAULT_LOCALE');
        $user1->currency = config('runtime.currency');
        $user1->latitude = $lat;
        $user1->longitude = $lng;
        $user1->setRememberToken(Str::random(60));
        $user1->confirmed = true;
        $user1->save();

        $user2 = new User();
        $user2->name = 'vendeur';
        $user2->email = 'vendeur@d.e';
        $user2->password = bcrypt('123456');
        $user2->locale = env('DEFAULT_LOCALE');
        $user2->currency = config('runtime.currency');
        $user2->latitude = $lat;
        $user2->longitude = $lng;
        $user2->setRememberToken(Str::random(60));
        $user2->confirmed = true;
        $user2->save();

        $user3 = new User();
        $user3->name = 'admin';
        $user3->email = 'admin@d.e';
        $user3->password = bcrypt('123456');
        $user3->locale = env('DEFAULT_LOCALE');
        $user3->currency = config('runtime.currency');
        $user3->latitude = $lat;
        $user3->longitude = $lng;
        $user3->setRememberToken(Str::random(60));
        $user3->confirmed = true;
        $user3->role='admin';
        $user3->save();

        $user4 = new User();
        $user4->name = 'delegation';
        $user4->email = 'delegation@d.e';
        $user4->password = bcrypt('123456');
        $user4->locale = env('DEFAULT_LOCALE');
        $user4->currency = env('DEFAULT_CURRENCY');
        $user4->compagnyName= env('DEFAULT_DELEGATE');
        $user4->registrationNumber = '123456789A123';
        $user4->latitude = 47.3478;
        $user4->longitude = 0.6486454740523868;
        $user4->geoloc= '[{"address_components":[{"long_name":"32","short_name":"32","types":["street_number"]},{"long_name":"Rue Gutenberg","short_name":"Rue Gutenberg","types":["route"]},{"long_name":"Joué-lès-Tours","short_name":"Joué-lès-Tours","types":["locality","political"]},{"long_name":"Indre-et-Loire","short_name":"Indre-et-Loire","types":["administrative_area_level_2","political"]},{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]},{"long_name":"37300","short_name":"37300","types":["postal_code"]}],"formatted_address":"32 Rue Gutenberg, 37300 Joué-lès-Tours, France","geometry":{"location":{"lat":47.3477464,"lng":0.6489845999999488},"location_type":"ROOFTOP","viewport":{"south":47.3463974197085,"west":0.647635619708467,"north":47.34909538029149,"east":0.6503335802915444}},"place_id":"ChIJDXcjat4p_UcRrdgENsC3RTM","types":["street_address"]},{"address_components":[{"long_name":"Joué-lès-Tours","short_name":"Joué-lès-Tours","types":["locality","political"]},{"long_name":"Indre-et-Loire","short_name":"Indre-et-Loire","types":["administrative_area_level_2","political"]},{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Joué-lès-Tours, France","geometry":{"bounds":{"south":47.299816,"west":0.6094590000000153,"north":47.375242,"east":0.6992960000000039},"location":{"lat":47.351861,"lng":0.661309899999992},"location_type":"APPROXIMATE","viewport":{"south":47.299816,"west":0.6094590000000153,"north":47.375242,"east":0.6992960000000039}},"place_id":"ChIJcWDGIhfW_EcRyR4q_XnK6Us","types":["locality","political"]},{"address_components":[{"long_name":"37300","short_name":"37300","types":["postal_code"]},{"long_name":"Joué-lès-Tours","short_name":"Joué-lès-Tours","types":["locality","political"]},{"long_name":"Indre-et-Loire","short_name":"Indre-et-Loire","types":["administrative_area_level_2","political"]},{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"37300 Joué-lès-Tours, France","geometry":{"bounds":{"south":47.2998031,"west":0.6092559999999594,"north":47.37539599999999,"east":0.6992147999999361},"location":{"lat":47.33938,"lng":0.6630261999999902},"location_type":"APPROXIMATE","viewport":{"south":47.2998031,"west":0.6092559999999594,"north":47.37539599999999,"east":0.6992147999999361}},"place_id":"ChIJx-sfRijW_EcRUAIKiNrIDRw","types":["postal_code"]},{"address_components":[{"long_name":"Indre-et-Loire","short_name":"Indre-et-Loire","types":["administrative_area_level_2","political"]},{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Indre-et-Loire, France","geometry":{"bounds":{"south":46.736714,"west":0.05273690000001352,"north":47.7098679,"east":1.3660489999999754},"location":{"lat":47.28949249999999,"lng":0.8160970000000134},"location_type":"APPROXIMATE","viewport":{"south":46.736714,"west":0.05273690000001352,"north":47.7098679,"east":1.3660489999999754}},"place_id":"ChIJE3AWTDjZ_EcR0CczBdfIDQM","types":["administrative_area_level_2","political"]},{"address_components":[{"long_name":"Centre-Val de Loire","short_name":"Centre-Val de Loire","types":["administrative_area_level_1","political"]},{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"Centre-Val de Loire, France","geometry":{"bounds":{"south":46.3469059,"west":0.05273690000001352,"north":48.941029,"east":3.1284098999999514},"location":{"lat":47.7515686,"lng":1.6750630999999885},"location_type":"APPROXIMATE","viewport":{"south":46.3469059,"west":0.05273690000001352,"north":48.941029,"east":3.1284098999999514}},"place_id":"ChIJiV0INnu55EcRMCUzBdfIDQE","types":["administrative_area_level_1","political"]},{"address_components":[{"long_name":"France","short_name":"FR","types":["country","political"]}],"formatted_address":"France","geometry":{"bounds":{"south":41.3253001,"west":-5.559099999999944,"north":51.1241999,"east":9.662499900000057},"location":{"lat":46.227638,"lng":2.213749000000007},"location_type":"APPROXIMATE","viewport":{"south":41.342778,"west":-5.142257900000004,"north":51.0891285,"east":9.55979339999999}},"place_id":"ChIJMVd4MymgVA0R99lHx5Y__Ws","types":["country","political"]}]';
        $user4->setRememberToken(Str::random(60));
        $user4->confirmed = true;
        $user4->role='delegation';
        $user4->save();

        $rootCategory1 = new Category();
        $rootCategory1->description = ['fr' => 'Matériels', 'en'=>'Hardware'];
        $rootCategory1->saveAsRoot();

        $rootCategory2 = new Category();
        $rootCategory2->description = ['fr' => 'Textiles', 'en'=>'Textils'];
        $rootCategory2->saveAsRoot();

        $subCategory11 = new Category();
        $subCategory11->description = ['fr' => 'crayons', 'en'=>'pens'];

        $subCategory12 = new Category();
        $subCategory12->description = ['fr' => 'electroniques', 'en'=>'electronics'];


        $parent1 = Category::find($rootCategory1->id);
        $parent1->appendNode($subCategory11);
        $parent1->appendNode($subCategory12);


        $subCategory21 = new Category();
        $subCategory21->description = ['fr' => 'Habillement', 'en'=>'Wearing'];

        $subCategory22 = new Category();
        $subCategory22->description = ['fr' => 'Sacs', 'en'=>'Bags'];

        $parent2 = Category::find($rootCategory2->id);
        $parent2->appendNode($subCategory21);
        $parent2->appendNode($subCategory22);


        $subCategory211 = new Category();
        $subCategory211->description = ['fr' => 'femme', 'en'=>'women'];

        $subCategory212 = new Category();
        $subCategory212->description = ['fr' => 'homme', 'en'=>'men'];

        $subCategory213 = new Category();
        $subCategory213->description = ['fr' => 'bi', 'en'=>'bi'];

        $parent21 = Category::find($subCategory21->id);
        $parent21->appendNode($subCategory211);
        $parent21->appendNode($subCategory212);
        $parent21->appendNode($subCategory213);




        // J-5
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
        return redirect(route('home'));
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

    //test if a resource is a picture. Only use for advertissement on welcome page
    public function isPicture(Request $request){
        $url = $request->url;
        if(array_key_exists('Content-Type', get_headers($url,1))){
            if(strpos(get_headers($url,1)['Content-Type'],'image') !== false){
                return response()->json(true);
            }
        }
        return response()->json(false);
    }

    public function tempo(){
        //test changement bio
        //$response = $this->vimeoManager->request('/me', ['bio'=>'essair'], 'PATCH');

        //test upload serveur
//        $content = Storage::disk('local')->url('public/1.mp4');
//        $content = ($_SERVER["DOCUMENT_ROOT"].'/videos/1.mp4');
//        $response = $this->vimeoManager->upload($content, false);
//        dd($response);

        //test changement param video
        //$response = json_decode(json_encode($this->vimeoManager->request('/me/videos')));
        //dd($response->body->data[0]->uri);
        //$response2 = $this->vimeoManager->request($response->body->data[0]->uri , ['privacy' => ['view' => 'anybody', 'download' => false]], 'PATCH');
        //$response2 = $this->vimeoManager->request($response->body->data[0]->uri , ['name' => 'Amazing video de ma face'], 'PATCH');
        dd(Advert::first()->priceWithMargin);
    }
}
