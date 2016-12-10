<?php

namespace App\Http\Controllers;


use App\Advert;
use App\Category;
use App\Common;
use App\Picture;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Symfony\Component\HttpFoundation\Request;
use App\Common\LocaleUtils;
use App\Common\MoneyUtils;

class UtilsController extends Controller
{

    use LocaleUtils;
    use MoneyUtils;

    public function __construct() {
        $this->middleware('auth', ['only' => ['getListCurrencies', 'getListLocales']]);
        $this->middleware('isAdminUser', ['only' => ['testGame', 'isPicture']]);
    }


    public function getListCurrencies()  {
        return response()->json($this->listCurrencies());
    }

    public function getListLocales() {
        return response()->json($this->listLocales());
    }

    public function testGame(){
        Artisan::call('migrate:refresh');

        $parameters = new Common();
        $parameters->save();

        $lat = 47.3526;
        $lng = 0.6702587142943912;


        $user1 = new User();
        $user1->name = 'client';
        $user1->email = 'client@d.e';
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




        $this->advertCreate(
            $user2->id,
            $subCategory212->id,
            '1000 doudounes en fin de série à prix cassées',
            ['11111111111111111111111111111111'],
            1000,
            50
        );

        $this->advertCreate(
            $user2->id,
            $subCategory213->id,
            'Un lot de 28 chiens de cirque',
            ['22222222222222222222222222222222', '33333333333333333333333333333333', '44444444444444444444444444444444'],
            28,
            8,
            'Ceci est une annonce à valider, ... ou pas!!??. Je vends un lot de 28 chiens de cirque. Il savent faire du trapeze, de la moto et font aussi la vaisselle.',
            true
        );

        $this->advertCreate(
            $user2->id,
            $subCategory12->id,
            'Excellent état! 10 Cartons de casques AudioBeats',
            ['55555555555555555555555555555555'],
            10,
            2
        );

        $this->advertCreate(
            $user2->id,
            $subCategory12->id,
            '10000 lunettes Gears pour samsung S7...\':(',
            ['66666666666666666666666666666666'],
            10000,
            500
        );

        $this->advertCreate(
            $user2->id,
            $subCategory11->id,
            '3 palettes de boites à dessin avec defauts',
            ['77777777777777777777777777777777'],
            3,
            1
        );

        $this->advertCreate(
            $user2->id,
            $subCategory22->id,
            '500 sacs à dos EastPack Gris à saisir',
            ['88888888888888888888888888888888'],
            500,
            30
        );

        $this->advertCreate(
            $user2->id,
            $subCategory11->id,
            '42 kilos de crayons tout venant',
            ['99999999999999999999999999999999'],
            42,
            5
        );

        $this->advertCreate(
            $user2->id,
            $subCategory12->id,
            '200 clés USB suite à depot de bilan',
            ['00000000000000000000000000000000'],
            200,
            50
        );

        return redirect(route('home'));
    }

    private function advertCreate($userId, $catId, $title, Array $pictures, $maxQuantity, $lotMini, $description=null, $isValid=null){
        $advert = new Advert();
        $advert->user_id = $userId;
        $advert->category_id = $catId;
        $advert->type = 'bid';
        $advert->title = $title;
        if($description){
            $advert->description = 'Ceci est une annonce à valider, ... ou pas!!??. Je vends un lot de 28 chiens de cirque. Il savent faire du trapeze, de la moto et font aussi la vaisselle.';
        } else {
            $advert->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus ullamcorper lectus, in semper dolor blandit id. Vestibulum quis orci convallis, elementum turpis a, scelerisque eros. Etiam dapibus sem libero, eu cursus elit aliquam facilisis. Nunc rutrum nulla purus, venenatis lobortis magna iaculis quis. Curabitur condimentum, lacus id ullamcorper pharetra, enim lectus hendrerit orci, sagittis cursus urna augue vel metus. Nunc egestas commodo felis, eu suscipit tortor. Duis at mauris ullamcorper, dapibus enim ut, imperdiet leo. Vestibulum mollis at mi quis vulputate. Donec eleifend, purus in sollicitudin sagittis, nibh dolor mattis nulla, sed condimentum lacus arcu at sapien. Donec ac turpis sit amet purus facilisis rutrum sit amet quis lacus. Pellentesque pharetra interdum turpis, sed varius ipsum scelerisque sed. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin augue magna, blandit molestie pharetra eget, sagittis quis tellus. Nullam quis molestie lorem. Cras at ornare diam, ut vulputate enim. Etiam enim mauris, auctor id pharetra nec, feugiat non purus.
Donec iaculis tellus eget ante sodales, vestibulum efficitur odio faucibus. Suspendisse non orci vitae quam rutrum pulvinar sit amet rhoncus metus. Suspendisse et ornare dui. Nullam mattis dui et nunc faucibus, ac rhoncus nibh consequat. Nullam arcu nisl, ultrices vel venenatis vitae, vehicula at odio. Phasellus suscipit viverra turpis id bibendum. Ut vestibulum venenatis ante eget ultrices. Nam et erat at tellus molestie scelerisque. Fusce in interdum dolor. Cras porta egestas lectus quis aliquet. Etiam quis arcu nisi. Ut pharetra non dui sit amet congue. Vivamus et ligula magna. Nulla malesuada, nunc id pulvinar pellentesque, tellus nisi congue est, id condimentum tellus tortor nec nisi. Aenean pretium fermentum tortor, eget auctor arcu tempor ac. Proin nibh tellus, scelerisque nec sagittis ut, posuere vitae enim. Cras non ligula efficitur, porta odio posuere.';
        }
        $advert->latitude = 47.3526;
        $advert->longitude = 0.6704089179992252;
        $advert->geoloc = 'Joué-lès-Tours, France';
        $advert->mainPicture = $pictures[0];
        $advert->currency="EUR";

        $advert->totalQuantity=$maxQuantity;
        $advert->lotMiniQuantity=$lotMini;
        $advert->isUrgent= filter_var(rand(0,1), FILTER_VALIDATE_BOOLEAN);

        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);

        $unitPrice = rand(1,20000)/100;

        $advert->price = $moneyParser->parse(strval($unitPrice),"EUR")->getAmount();

        $advert->cost = 0;
        $advert->isPublish = true;

        if($isValid==null) {
            $loterie = rand(0,1);
            if($loterie==1){
               $advert->isValid = true;
            }
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
        return null;
    }
}
