<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Category;
use App\Common\DBUtils;
use App\Common\MoneyUtils;
use App\Common\PicturesManager;
use App\Http\Requests\StoreAdvertRequest;
use App\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class AdvertController extends Controller
{
    private $pictureManager;

    public function __construct(PicturesManager $picturesManager) {
        $this->middleware('auth', ['except' => ['index', 'show', 'getListType']]);
        $this->middleware('haveCompleteAccount', ['only' => ['publish']]);
        $this->middleware('isAdminUser', ['only' => ['toApprove','listApprove', 'approve']]);
        $this->pictureManager  = $picturesManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Init vars
        $isSearchRequest = ($request->has('search') && strlen($request->search) >= 3);
        $isSearchResults = ($request->has('resultsFor') && strlen($request->resultsFor) >= 3);
        $isRangePricesOnly = ($request->has('priceOnly') && filter_var($request->priceOnly, FILTER_VALIDATE_BOOLEAN) == true);
        $isUrgentOnly = ($request->has('isUrgent') && filter_var($request->isUrgent, FILTER_VALIDATE_BOOLEAN) == true );

        //only valid advert
        $adverts = Advert::where('isValid', true);

        //where currency
        if($request->has('currency')){
            $currencies = new ISOCurrencies();
            if($currencies->contains(new Currency($request->currency))) {
                $currency = $request->currency;
            } else {
                $currency = env('DEFAULT_CURRENCY');
            }
        } else {
            $currency = env('DEFAULT_CURRENCY');
        }
        $adverts = $adverts->where('currency', $currency);


        if($request->has('categoryId') && $request->categoryId != 0){
            $categories = Category::with('descendants')->where('id', $request->categoryId)->get()->toFlatTree();
            if(count($categories)==1){
                $category = $categories[0];
                $ids = [];
                $ids[] = $category->id;
                foreach ($category->descendants as $descendant){
                    $ids[] = $descendant->id;
                }
                $adverts = $adverts->whereIn('category_id', $ids);
            }
        }

        //search results before range price
        if($isSearchResults){
            $search = $request->resultsFor;
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%');
            });
        }

        //Set min & max prices only if not $isSearchRequest
        if(!$isSearchRequest) {
            $minAllPrice = $adverts->min('price');
            $maxAllPrice = $adverts->max('price');

            if ($minAllPrice) {
                $minAllPrice = MoneyUtils::getPriceWithDecimal($minAllPrice, $currency, false);
            } else {
                $minAllPrice = 0;
            }

            if ($maxAllPrice) {
                $maxAllPrice = MoneyUtils::getPriceWithDecimal($maxAllPrice, $currency, false);
            } else {
                $maxAllPrice = 0;
            }
        }

        //STOP REQUEST HERE IF only RANGE PRICES
        if($isRangePricesOnly){
            return response()->json(['minPrice'=> $minAllPrice, 'maxPrice' => $maxAllPrice]);
        }

        //if urgent
        if($isUrgentOnly){
            $adverts = $adverts->where('isUrgent', true);
        }

        //if range price
        if($request->has('minPrice') && $request->has('maxPrice') ){
            $minPrice = MoneyUtils::setPriceWithoutDecimal($request->minPrice, $currency);
            $maxPrice = MoneyUtils::setPriceWithoutDecimal($request->maxPrice, $currency);
            $adverts = $adverts->where('price', '>=', $minPrice)->where('price', '<=', $maxPrice);
        }

        if($isSearchRequest){
            $search = $request->search;
            $adverts = $adverts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' .$search .'%')
                    ->orWhere('description', 'LIKE', '%' .$search .'%');
            });
        }

        if($isSearchRequest) {
            $countSearch = $adverts->count();
            $adverts = $adverts->orderBy('updated_at', 'desc')->limit(config('runtime.maxNumberOfSearchResults'))->get();
        } else {
            $adverts = $adverts->orderBy('updated_at', 'desc')->paginate(config('runtime.advertsPerPage'));
        }

        $adverts->load('pictures');
        $adverts->load('category');
        $tempoStore =[];
        $resultsByCat = [];
        foreach ($adverts as $advert){
            if(!array_key_exists($advert->category->id,$tempoStore)){
                $ancestors = $advert->category->getAncestors();
                $ancestors->add($advert->category);
                $tempoStore[$advert->category->id] = $ancestors;
                $resultsByCat[$advert->category->id]['results'] = [];
            } else {
                $ancestors = $tempoStore[$advert->category->id];
            }
            $advert->setBreadCrumb($ancestors);
            $resultsByCat[$advert->category->id]['results'][] = $advert;
            $resultsByCat[$advert->category->id]['name'] = $advert->getConstructBreadCrumb();
        }

        if($isSearchRequest){
            $action = [
                'url' => '#',
                'text' => trans_choice('strings.form_input_search_view_all', $countSearch, ['nb' => $countSearch])
            ];
            return response()->json(['results'=> $resultsByCat, 'action' => $action]);
        } else {
            return response()->json(['adverts'=> $adverts, 'minPrice'=> $minAllPrice, 'maxPrice' => $maxAllPrice]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //TODO changer ip
        //$ip = $request->ip();
        $ip='82.246.117.210';
        $geolocType = 1;
        $zoomMap = 11;
        return view('advert.create', compact('ip', 'geolocType', 'zoomMap'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(StoreAdvertRequest $request)
    {
        $category = Category::find($request->category);
        if($category) {
            try {
                $advert = new Advert();
                $advert->user_id = auth()->user()->id;
                $advert->category_id = $request->category;
                $advert->type = $request->type;
                $advert->title = $request->title;
                $advert->description = $request->description;
                $advert->latitude = $request->lat;
                $advert->longitude = $request->lng;
                $advert->geoloc = $request->geoloc;
                $advert->mainPicture = $request->main_picture;
                $advert->currency=$request->currency;
                $advert->totalQuantity=$request->total_quantity;
                $advert->lotMiniQuantity=$request->lot_mini_quantity;
                $advert->isUrgent=filter_var($request->is_urgent, FILTER_VALIDATE_BOOLEAN);

                $advert->price = MoneyUtils::setPriceWithoutDecimal($request->price,$request->currency);

                $results = $this->pictureManager->storeLocalFinal();

                $advert->cost = $this->getCost(count($results), $advert->isUrgent);

                DB::beginTransaction();
                $advert->save();
                foreach ($results as $result){
                    $picture = new Picture();
                    $picture->hashName = $result['hashName'];
                    $picture->path = $result['path'];
                    $picture->disk = $result['disk'];
                    $picture->isThumb = $result['isThumb'];
                    $advert->pictures()->save($picture);
                    $picture->save();
                }
                DB::commit();
                return redirect(route('user.completeAccount', ['id' =>$advert->id]));
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->withInput()->withErrors(trans('strings.view_all_error_saving_message'));
            }
        } else {
            return redirect()->back()->withInput()->withErrors(trans('strings.view_all_error_saving_message'));
        }
    }

    private function getCost($nbPictures, $isUrgent=false){
        $cost = 0;
        if($nbPictures > config('runtime.nbFreePictures')){
            $cost += ($nbPictures - config('runtime.nbFreePictures'))*10;
        }

        if($isUrgent){
            $cost += config('runtime.urgentCost');
        }

        return $cost;
    }

    public function cost($nbPictures, $isUrgent) {
        if(isset($nbPictures) && isset($isUrgent) && is_numeric($nbPictures)){
            return response()->json($this->getCost((int)$nbPictures,filter_var($isUrgent, FILTER_VALIDATE_BOOLEAN)));
        } else {
            return response('error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $advert = Advert::find($id);
        if($advert->isValid){
            dd($advert->formattedAdress);
        } else {
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getListType()  {
        $list = DBUtils::getEnumValues('adverts', 'type');
        $transList = [];
        foreach ($list as $key => $item) {
            $transList[$key] = trans('strings.view_advert_list_type_' . $item);
        }
        return response()->json($transList);
    }

    public function toApprove() {
        return view('advert.approve');
    }

    public function listApprove() {
        $adverts = Advert::where('isPublish', true)->where('isValid', null)->get();
        $adverts->load('user');
        $adverts->load('pictures');
        $adverts->load('category');
        return response()->json($adverts);
    }

    public function approve(Request $request) {

        $approveList = $request->all();
        try {
            foreach ($approveList as $key=>$value) {
                if($value != null) {
                    $advert = Advert::find($key);
                    if($advert) {
                        $advert->isValid=(boolean)$value;
                        $advert->save();
                        /*TODO envoyer mail au client */
                    }
                }
            }
        } catch (\Exception $e) {
            return response(trans('strings.view_advert_error'), 500);
        }

        return response('ok',200);
    }

    public function publish(Request $request, $id){
        $advert = Advert::find($id);
        $advert->load('user');
        if($advert && $advert->user->id == auth()->user()->id && $advert->cost == 0){
            $advert->isPublish = true;
            $advert->save();
            $this->pictureManager->purgeLocalTempo();
            $request->session()->flash('clear', true);
            return redirect(route('home'))->with('success', trans('strings.advert_create_success'));
        } elseif($advert && $advert->user->id == auth()->user()->id && $advert->cost > 0) {
            $payment = $advert->payment();
            //TODO fin du test et redirection
            $advert->isPublish = true;
            $advert->save();
            $this->pictureManager->purgeLocalTempo();
            $request->session()->flash('clear', true);
            return redirect(route('home'))->with('success', trans('strings.advert_create_success'));
        } else {
            return response(trans('strings.view_advert_error'), 500);
        }
    }
}
