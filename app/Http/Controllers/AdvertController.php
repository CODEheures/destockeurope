<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Category;
use App\Common\DBUtils;
use App\Http\Requests\StoreAdvertRequest;
use Illuminate\Http\Request;
use League\Flysystem\Exception;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class AdvertController extends Controller
{

    public function __construct() {
        $this->middleware('auth', ['only' => ['create', 'getListType', 'getListCurrencies']]);
        $this->middleware('isAdminUser', ['only' => ['toApprove','listApprove', 'approve']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adverts = Advert::where('isValid', true)->get();
        //dd($adverts[0]->price);
        return response()->json($adverts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('advert.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdvertRequest $request)
    {
        $category = Category::find($request->category);
        if($category) {
            $advert = new Advert();
            $advert->user_id = auth()->user()->id;
            $advert->category_id = $request->category;
            $advert->type = $request->type;
            $advert->title = $request->title;
            $advert->description = $request->description;
            $advert->currency=$request->currency;

            $currencies = new ISOCurrencies();
            $moneyParser = new DecimalMoneyParser($currencies);

            $advert->price = $moneyParser->parse($request->price,$request->currency)->getAmount();

            $advert->save();
            return redirect(route('home'))->with('success', trans('strings.advert_create_success'));
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
        //
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
        $adverts = Advert::where('isValid', null)->get();
        $adverts->load('user');
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
        } catch (Exception $e) {
            return response(trans('strings.view_advert_error'), 500);
        }

        return response('ok',200);
    }
}
