<?php

namespace App\Http\Controllers;


use App\Advert;
use App\Category;
use App\Common\PicturesManager;
use App\Picture;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use NumberFormatter;

class UtilsController extends Controller
{
    public function getListCurrencies()  {
        $currencies = new ISOCurrencies();

        $listCodeCurrencies=[];
        foreach ($currencies as $currency) {

            $region = auth()->user()->locale."@currency=$currency";
            $formatter = new NumberFormatter($region, NumberFormatter::CURRENCY);
            $symbol = $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);

            $listCodeCurrencies[$currency->getCode()] = [
                'code' => $currency->getCode(),
                'symbol' => $symbol];
        }

        $userPreferedCurrency = auth()->user()->currency;
        $response = [
            'listCurrencies' => $listCodeCurrencies,
            'userPrefCurrency' => $userPreferedCurrency
        ];
        return response()->json($response);
    }

    public function getListLocales() {
        $locales = \ResourceBundle::getLocales('');

        $listLocales = [];
        foreach ($locales as $locale) {
            $listLocales[$locale] = [
                'code' => $locale,
                'name' => \Locale::getDisplayName($locale),
                'region' => strtolower(\Locale::getDisplayRegion($locale))
            ];
        }

        $userPreferedLocale = auth()->user()->locale;
        $response = [
            'listLocales' => $listLocales,
            'userPrefLocale' => $userPreferedLocale
        ];

        return response()->json($response);
    }

    public function testGame(){
        Artisan::call('migrate:refresh');

        $user1 = new User();
        $user1->name = 'client';
        $user1->email = 'client@d.e';
        $user1->password = bcrypt('123456');
        $user1->setRememberToken(Str::random(60));
        $user1->confirmed = true;
        $user1->save();

        $user2 = new User();
        $user2->name = 'admin';
        $user2->email = 'admin@d.e';
        $user2->password = bcrypt('123456');
        $user2->setRememberToken(Str::random(60));
        $user2->confirmed = true;
        $user2->role='admin';
        $user2->save();

        $rootCategory1 = new Category();
        $rootCategory1->description = ['fr' => 'Sacs', 'en'=>'Bags'];
        $rootCategory1->saveAsRoot();

        $rootCategory2 = new Category();
        $rootCategory2->description = ['fr' => 'Crayons', 'en'=>'Pens'];
        $rootCategory2->saveAsRoot();

        $subCategory11 = new Category();
        $subCategory11->description = ['fr' => 'à main', 'en'=>'Hands'];

        $subCategory12 = new Category();
        $subCategory12->description = ['fr' => 'à dos', 'en'=>'Back'];

        $subCategory13 = new Category();
        $subCategory13->description = ['fr' => 'de ranonnées', 'en'=>'Hiking'];

        $parent1 = Category::find($rootCategory1->id);
        $parent1->appendNode($subCategory11);
        $parent1->appendNode($subCategory12);
        $parent1->appendNode($subCategory13);

        $subCategory21 = new Category();
        $subCategory21->description = ['fr' => 'à bille', 'en'=>'Ball'];

        $subCategory22 = new Category();
        $subCategory22->description = ['fr' => 'à plûme', 'en'=>'Pencil'];

        $subCategory23 = new Category();
        $subCategory23->description = ['fr' => 'de couleur', 'en'=>'Color'];

        $parent2 = Category::find($rootCategory2->id);
        $parent2->appendNode($subCategory21);
        $parent2->appendNode($subCategory22);
        $parent2->appendNode($subCategory23);

        $subCategory111 = new Category();
        $subCategory111->description = ['fr' => 'femme', 'en'=>'women'];

        $subCategory112 = new Category();
        $subCategory112->description = ['fr' => 'homme', 'en'=>'men'];

        $subCategory113 = new Category();
        $subCategory113->description = ['fr' => 'bi', 'en'=>'bi'];

        $parent11 = Category::find($subCategory11->id);
        $parent11->appendNode($subCategory111);
        $parent11->appendNode($subCategory112);
        $parent11->appendNode($subCategory113);

        $subCategory231 = new Category();
        $subCategory231->description = ['fr' => 'Vert', 'en'=>'Green'];

        $subCategory232 = new Category();
        $subCategory232->description = ['fr' => 'Jaune', 'en'=>'Yellow'];

        $subCategory233 = new Category();
        $subCategory233->description = ['fr' => 'Rouge', 'en'=>'Red'];

        $parent23 = Category::find($subCategory23->id);
        $parent23->appendNode($subCategory231);
        $parent23->appendNode($subCategory232);
        $parent23->appendNode($subCategory233);




        $advert = new Advert();
        $advert->user_id = $user1->id;
        $advert->category_id = $subCategory12->id;
        $advert->type = 'bid';
        $advert->title = 'Un lot de 50 sacs à dos';
        $advert->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus ullamcorper lectus, in semper dolor blandit id. Vestibulum quis orci convallis, elementum turpis a, scelerisque eros. Etiam dapibus sem libero, eu cursus elit aliquam facilisis. Nunc rutrum nulla purus, venenatis lobortis magna iaculis quis. Curabitur condimentum, lacus id ullamcorper pharetra, enim lectus hendrerit orci, sagittis cursus urna augue vel metus. Nunc egestas commodo felis, eu suscipit tortor. Duis at mauris ullamcorper, dapibus enim ut, imperdiet leo. Vestibulum mollis at mi quis vulputate. Donec eleifend, purus in sollicitudin sagittis, nibh dolor mattis nulla, sed condimentum lacus arcu at sapien. Donec ac turpis sit amet purus facilisis rutrum sit amet quis lacus. Pellentesque pharetra interdum turpis, sed varius ipsum scelerisque sed. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin augue magna, blandit molestie pharetra eget, sagittis quis tellus. Nullam quis molestie lorem. Cras at ornare diam, ut vulputate enim. Etiam enim mauris, auctor id pharetra nec, feugiat non purus.
Donec iaculis tellus eget ante sodales, vestibulum efficitur odio faucibus. Suspendisse non orci vitae quam rutrum pulvinar sit amet rhoncus metus. Suspendisse et ornare dui. Nullam mattis dui et nunc faucibus, ac rhoncus nibh consequat. Nullam arcu nisl, ultrices vel venenatis vitae, vehicula at odio. Phasellus suscipit viverra turpis id bibendum. Ut vestibulum venenatis ante eget ultrices. Nam et erat at tellus molestie scelerisque. Fusce in interdum dolor. Cras porta egestas lectus quis aliquet. Etiam quis arcu nisi. Ut pharetra non dui sit amet congue. Vivamus et ligula magna. Nulla malesuada, nunc id pulvinar pellentesque, tellus nisi congue est, id condimentum tellus tortor nec nisi. Aenean pretium fermentum tortor, eget auctor arcu tempor ac. Proin nibh tellus, scelerisque nec sagittis ut, posuere vitae enim. Cras non ligula efficitur, porta odio posuere.';
        $advert->latitude = 47.3526;
        $advert->longitude = 0.6704089179992252;
        $advert->geoloc = 'Joué-lès-Tours, France';
        $advert->mainPicture = '11111111111111111111111111111111';
        $advert->currency="EUR";
        $advert->totalQuantity='700';
        $advert->lotMiniQuantity='50';
        $advert->urgent=true;

        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);
        $advert->price = $moneyParser->parse('100',"EUR")->getAmount();

        $advert->cost = 0;
        $advert->isPublish = true;
        $advert->isValid = true;

        DB::beginTransaction();
        $advert->save();
        $picture = new Picture();
        $picture->hashName = '11111111111111111111111111111111';
        $picture->path = '/final/1/';
        $picture->disk = 'local';
        $picture->isThumb = false;
        $advert->pictures()->save($picture);
        $picture->save();

        $picture = new Picture();
        $picture->hashName = '11111111111111111111111111111111';
        $picture->path = '/final/1/';
        $picture->disk = 'local';
        $picture->isThumb = true;
        $advert->pictures()->save($picture);
        $picture->save();

        DB::commit();


        $advert = new Advert();
        $advert->user_id = $user1->id;
        $advert->category_id = $subCategory12->id;
        $advert->type = 'bid';
        $advert->title = 'Un lot de 28 chiens de cirque';
        $advert->description = 'Ceci est une annonce à valider, ... ou pas!!??. Je vends un lot de 28 chiens de cirque. Il savent faire du trapeze, de la moto et font aussi la vaisselle.';
        $advert->latitude = 47.3526;
        $advert->longitude = 0.6704089179992252;
        $advert->geoloc = 'Joué-lès-Tours, France';
        $advert->mainPicture = '22222222222222222222222222222222';
        $advert->currency="EUR";
        $advert->totalQuantity='28';
        $advert->lotMiniQuantity='28';
        $advert->urgent=true;

        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);
        $advert->price = $moneyParser->parse('2800',"EUR")->getAmount();

        $advert->cost = 0;
        $advert->isPublish = true;

        DB::beginTransaction();
        $advert->save();
        $picture = new Picture();
        $picture->hashName = '22222222222222222222222222222222';
        $picture->path = '/final/1/';
        $picture->disk = 'local';
        $picture->isThumb = false;
        $advert->pictures()->save($picture);
        $picture->save();

        $picture = new Picture();
        $picture->hashName = '22222222222222222222222222222222';
        $picture->path = '/final/1/';
        $picture->disk = 'local';
        $picture->isThumb = true;
        $advert->pictures()->save($picture);
        $picture->save();

        $picture = new Picture();
        $picture->hashName = '33333333333333333333333333333333';
        $picture->path = '/final/1/';
        $picture->disk = 'local';
        $picture->isThumb = false;
        $advert->pictures()->save($picture);
        $picture->save();

        $picture = new Picture();
        $picture->hashName = '33333333333333333333333333333333';
        $picture->path = '/final/1/';
        $picture->disk = 'local';
        $picture->isThumb = true;
        $advert->pictures()->save($picture);
        $picture->save();

        $picture = new Picture();
        $picture->hashName = '44444444444444444444444444444444';
        $picture->path = '/final/1/';
        $picture->disk = 'local';
        $picture->isThumb = false;
        $advert->pictures()->save($picture);
        $picture->save();

        $picture = new Picture();
        $picture->hashName = '44444444444444444444444444444444';
        $picture->path = '/final/1/';
        $picture->disk = 'local';
        $picture->isThumb = true;
        $advert->pictures()->save($picture);
        $picture->save();

        DB::commit();

    }

    public function tempo(){
        $disk = Storage::disk('local');
        return $disk;
    }
}
