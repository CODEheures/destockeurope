<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Category;
use App\Common\DBUtils;
use App\Common\PicturesManager;
use App\Http\Requests\StoreAdvertRequest;
use App\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

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
        $adverts = Advert::where('isValid', true);

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

        $adverts = $adverts->orderBy('updated_at', 'desc')->paginate(config('runtime.advertsPerPage'));


        $adverts->load('pictures');
        $adverts->load('category');
        foreach ($adverts as $advert){
            $ancestors = $advert->category->getAncestors();
            $ancestors->add($advert->category);
            $advert->setBreadCrumb($ancestors);
        }
        return response()->json($adverts);
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

                $currencies = new ISOCurrencies();
                $moneyParser = new DecimalMoneyParser($currencies);
                $advert->price = $moneyParser->parse($request->price,$request->currency)->getAmount();

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
