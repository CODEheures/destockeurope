<?php

namespace App\Http\Controllers;


use App\Advert;
use App\Anonymous;
use App\Common\AdvertUtils;
use App\Common\CostUtils;
use App\Common\LocaleUtils;
use App\Common\MoneyUtils;
use App\Common\PrivilegesUtils;
use App\Common\UserUtils;
use App\Http\Requests\SubscribeNewsLetterRequest;
use App\Http\Requests\UnsubscribeNewsLetterRequest;
use App\Notifications\GlobalMessage;
use App\Picture;
use App\User;
use Codeheures\LaravelUtils\Traits\Tools\Browser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => [
            'portal',
            'subscribeNewsLetter',
            'getUnsubscribeNewsLetter',
            'postUnsubscribeNewsLetter',
            'main',
            'home',
            'whoAreWe',
            'joinUs',
            'environmentalImpact',
            'ads',
            'legalMentions',
            'cgu',
            'diffusionRules',
            'cgv',
            'help',
            'contact',
            'contactPost',
            'prices',
            'imageServer'
        ]]);
        $this->middleware('isEmailConfirmed', ['only' => ['mines']]);
        $this->middleware('canGetMines', ['only' => ['mines']]);
        $this->middleware('canGetBookmarks', ['only' => ['bookmarks']]);
    }

    /**
     * Return View Of Portal APP
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function portal() {
        $masterAdsControllerFlag = false;
        $browser = Browser::getBrowserName();
        return view('portal', compact('masterAdsControllerFlag', 'browser'));
    }

    /**
     * Process to newsletter subrcription XMLHttpRequest
     * @param SubscribeNewsLetterRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function subscribeNewsLetter(SubscribeNewsLetterRequest $request) {
        try {
            UserUtils::createOrUpdateAnonymous($request->email, $request->name, $request->phone, null, true);
            return response(trans('strings.view_portal_newsletter_subscribe_success'), 200);
        } catch (\Exception $e) {
            return response(trans('strings.view_portal_newsletter_subscribe_error'), 500);
        }
    }

    /**
     *
     * Return the view for unsubscribe newsletter
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUnsubscribeNewsLetter() {
        return view('user.unsubscribeNewsLetter');
    }

    /**
     * Process to newsletter unsubrcription XMLHttpRequest
     * @param SubscribeNewsLetterRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function postUnsubscribeNewsLetter(UnsubscribeNewsLetterRequest $request) {
        try {
            $existUser = Anonymous::whereMail($request->email)->first();
            if($existUser){
                UserUtils::updateAnonymous($existUser, null, null, null, false);
            }
            return redirect('home')->with('success', trans('strings.view_portal_newsletter_unsubscribe_success'));
        } catch (\Exception $e) {
            return redirect('home')->withErrors(trans('strings.view_portal_newsletter_subscribe_error'));
        }
    }

    /**
     * Return Home Adverts View
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function main() {
        return redirect(route('home'));
    }

    /**
     * Return Home Adverts View
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home(Request $request) {
        $masterAdsControllerFlag = true;

        $fakeCost = CostUtils::getCostIsHighlight(true);

        $fakeHighlightAdvert = new Advert();
        $fakeHighlightAdvert->isNegociated = false;
        $fakeHighlightAdvert->title = trans('strings.view_home_fake_advert_title', ['price' => MoneyUtils::getPriceWithDecimal($fakeCost, 'EUR',true)]);
        $fakeHighlightAdvert->price = $fakeCost;
        $fakeHighlightAdvert->currency = 'EUR';
        $fakeHighlightAdvert->totalQuantity = env('HIGHLIGHT_HOURS_DURATION');
        $fakeHighlightAdvert->user_id = User::whereRole(User::ROLE_USER)->first()->id;
        $fakeHighlightAdvert = $fakeHighlightAdvert->toArray();

        $path = 'images/fake_advert_' . App::getLocale() . '.jpg';
        if(file_exists(__DIR__ . '/../../../public/'. $path)){
            $pic_asset = asset($path);
        } else {
            $pic_asset = asset('images/fake_advert_en.jpg');
        }
        $fakeHighlightAdvertPicture = new Picture();
        $fakeHighlightAdvertPicture->thumbUrl = $pic_asset;
        $fakeHighlightAdvertPicture->normalUrl = $pic_asset;

        $fakeHighlightAdvertPicture = $fakeHighlightAdvertPicture->toArray();
        $fakeHighlightAdvert['pictures'][] = $fakeHighlightAdvertPicture;

        $fakeHighlightAdvert['price_margin'] = $fakeHighlightAdvert['price'];
        $fakeHighlightAdvert['url'] = route('mines');

        $location = $request->filled('forLocation') ?  $request->forLocation : null;

        $countryName = null;
        $countryCode = null;
        if(!is_null($location)){
            $countries = LocaleUtils::getListCountries();
            foreach ($countries as $country){
                if(in_array($location, $country)){
                    $countryCode = $country['code'];
                    $countryName = $country['name'];
                    $request->replace(array_merge($request->all(), ['country' => $countryCode]));
                    break;
                }
            }
        }


        $isSearchRequest = ($request->filled('search') && strlen($request->search) >= config('runtime.minLengthSearch'));

        if(!$isSearchRequest){
            $ranges = AdvertUtils::getRangePriceOnly($request, $countryCode);

            if(!$request->minPrice || $request->minPrice < $ranges['minPrice'] || $request->minPrice > $ranges['maxPrice']){
                $request->replace(array_merge($request->all(), ['minPrice' => $ranges['minPrice']]));
            }

            if(!$request->maxPrice || $request->maxPrice < $ranges['minPrice'] || $request->maxPrice > $ranges['maxPrice']){
                $request->replace(array_merge($request->all(), ['maxPrice' => $ranges['maxPrice']]));
            }

            if(!$request->minQuantity || $request->minQuantity < $ranges['minQuantity'] || $request->minQuantity > $ranges['maxQuantity']){
                $request->replace(array_merge($request->all(), ['minQuantity' => $ranges['minQuantity']]));
            }

            if(!$request->maxQuantity || $request->maxQuantity < $ranges['minQuantity'] || $request->maxQuantity > $ranges['maxQuantity']){
                $request->replace(array_merge($request->all(), ['maxQuantity' => $ranges['maxQuantity']]));
            }
        }



        $advertsList = AdvertUtils::getList($request);

        if(!$isSearchRequest){
            $advertsList['adverts'] = $advertsList['adverts']->toArray();
            return view('welcome', compact('masterAdsControllerFlag', 'countryName', 'fakeHighlightAdvert', 'ranges', 'advertsList'));
        } else {
            return response()->json($advertsList);
        }

    }

    /**
     * Return list of Mines Adverts XMLHttpRequest
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mines() {
        $advertsList['adverts'] = AdvertUtils::getPersonnalList();
        return view('user.personnalList', compact('advertsList'));
    }

    /**
     * Return View of my bookmarks adverts
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookmarks() {
        $advertsList['adverts'] = AdvertUtils::getBookmarkList();
        return view('user.bookmarksList', compact('advertsList'));
    }

    /**
     * Return view of whoAreWe
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function whoAreWe() {
        return view('global.whoAreWe');
    }

    /**
     * Return view of joinUS
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function joinUs() {
        return view('global.joinUs');
    }

    /**
     * Return view of environmentalImpact
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function environmentalImpact() {
        return view('global.environmentalImpact');
    }

    /**
     * Return view of ads
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ads() {
        return view('global.ads');
    }


    /**
     * Return view of legalMentions
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function legalMentions() {
        $randomAdvertUrl = route('advert.show',['slug' => 'montres-lexon-moon-3615882']);
        $randomAdvertId = 3615882;

        return view('global.legalMentions', compact('randomAdvertUrl', 'randomAdvertId'));
    }

    /**
     * Return view of CGV
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cgu(){
        return view('global.cgu');
    }

    /**
     * Return view of diffusionRules
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function diffusionRules() {
        return view('global.diffusionRules');
    }

    /**
     * Return view of CGV
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cgv(){
        return view('global.cgv');
    }

    /**
     * Return view of CGV
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function help(){
        return view('global.help');
    }

    /**
     * Return view of contact
     * @return string
     */
    public function contact(){
        return view('global.contact');
    }

    public function contactPost(Request $request){
        //valid Request here because not possible back in pop-up message
        $validator = Validator::make($request->all(), [
            'message' => 'required|min:' . config('db_limits.messages.minLength') . '|max:' . config('db_limits.messages.maxLength'),
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors((trans('strings.mail_report_send_error')));
        }

        $senderMail = $request->email;
        $name = $request->name;
        $message = $request->message;

        $recipients = User::whereIn('role', PrivilegesUtils::canReceiveContact())->get();
        foreach ($recipients as $recipient){
            $recipient->notify(new GlobalMessage($senderMail, $name, $message));
        }

        return redirect(route('home'))->with('success', trans('strings.view_contact_success'));
    }

    /**
     * Return view of CGV
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function prices(){
        return view('global.prices');
    }

    public function imageServer(Request $request) {
        $clientResponse=null;

        if($request->filled('url') && filter_var($request->url, FILTER_VALIDATE_URL)){
            $client = new Client();
            $clientResponse = $client->request('GET',
                $request->url,
                [
                    'http_errors' => false,
                ]
            );
        }


        if(!is_null($clientResponse) && $clientResponse->getStatusCode()<300){
            return response($clientResponse->getBody()->getContents(),200)->header("Content-Type", $clientResponse->getHeader("Content-Type"));
        } else {
            return response('not found',404);
        }
    }
}
