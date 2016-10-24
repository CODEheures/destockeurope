<?php

namespace App\Http\Controllers;

use App\MetaCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class MetaCategoryController extends Controller
{

    public function __construct() {
        $this->middleware('isAdminUser', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $metaCategories = MetaCategory::all();
        $metaCategories->load('categories');
        return response()->json($metaCategories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $descriptions = $request->descriptions;

        //1 test langages in lang
        foreach ($descriptions as $lang=>$description){
            if(!in_array($lang,config('app.locales'))){
                return response('error', 500);
            }
        }

        //2 test exist MetaCategories
        foreach ($descriptions as $lang=>$description){
            $existMetaCategory = MetaCategory::where('description', 'LIKE', '%"' .$lang .'":"' .$description.'"%')->first();
            if($existMetaCategory){
                return response(trans('strings.view_category_add_exist'), 409);
            }
        }

        //Finaly
        $meta = new MetaCategory();
        $meta->description = $descriptions;
        $meta->save();
        return response('ok',201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $metaCategory = MetaCategory::find($id);
        if($metaCategory){
            $metaCategory->load('categories');
            return response()->json($metaCategory);
        } else {
            return response('error',500);
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

        $descriptions = $request->description;

        //1 test langages in lang
        foreach ($descriptions as $lang=>$description){
            if(!in_array($lang,config('app.locales'))){
                return response('error', 500);
            }
        }

        //2 test exist MetaCategories
        $existMetaCategory = MetaCategory::find($id);
        if(!$existMetaCategory){
            return response(trans('strings.view_category_patch_not_exist'), 409);
        }

        //Finaly
        $existDescription = $existMetaCategory->description;
        foreach ($descriptions as $lang=>$description){
            $existDescription[$lang] = $description;
        }
        $existMetaCategory->description = $existDescription;
        $existMetaCategory->save();
        return response('ok',201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id && $id > 0 ) {
            $existMetaCategory = MetaCategory::find($id);
            if(!$existMetaCategory) {
                return response(trans('strings.view_category_del_not_exist'), 409);
            } else {
                $categories = $existMetaCategory->categories;
                foreach ($categories as $category){
                    $category->delete();
                }
                $existMetaCategory->delete();
                return response('ok',200);
            }
        } else {
            return response('error', 500);
        }
    }
}
