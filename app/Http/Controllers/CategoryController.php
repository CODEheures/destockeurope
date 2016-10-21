<?php

namespace App\Http\Controllers;

use App\Category;
use App\MetaCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function __construct() {
        $this->middleware('isAdminUser', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $metaCategories = MetaCategory::all();
//        $metaCategories->load('categories');
//        return view('category.index', compact('metaCategories'));

        return view('category.index');
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
        $parentId = $request->parentId;
        $metaCategoryId = $request->metaCategoryId;

        //1 test langages in lang
        foreach ($descriptions as $lang=>$description){
            if(!in_array($lang,config('app.locales'))){
                return response('error', 500);
            }
        }

        //2 test exist MetaCategory
        $existMetaCategory = MetaCategory::find($metaCategoryId);
        if(!$existMetaCategory){
            return response(trans('strings.view_category_add_parent_not_exist'), 409);
        }

        //3 test exist category
        foreach ($descriptions as $lang=>$description){
            $existCategory = Category::where('meta_category_id', '=', $metaCategoryId)
                ->where('parent_id', '=', $parentId)
                ->where('description', 'LIKE', '%"' .$lang .'":"' .$description.'"%')
                ->first();
            if($existCategory){
                return response(trans('strings.view_category_add_exist'), 409);
            }
        }

        //Finaly
        $cat = new Category();
        $cat->description = $descriptions;
        $cat->metaCategory()->associate($metaCategoryId);
        $cat->parent_id = $parentId;
        $cat->save();
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
        $descriptions = $request->description;

        //1 test langages in lang
        foreach ($descriptions as $lang=>$description){
            if(!in_array($lang,config('app.locales'))){
                return response('error', 500);
            }
        }

        //2 test exist MetaCategories
        $existCategory = Category::find($id);
        if(!$existCategory){
            return response(trans('strings.view_category_patch_not_exist'), 409);
        }

        //Finaly
        $existDescription = $existCategory->description;
        foreach ($descriptions as $lang=>$description){
            $existDescription[$lang] = $description;
        }
        $existCategory->description = $existDescription;
        $existCategory->save();
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
            $existCategory = Category::find($id);
            if(!$existCategory) {
                return response(trans('strings.view_category_del_not_exist'), 409);
            } else {
                Category::where('parent_id', '=', $id)->delete();
                $existCategory->delete();
                return response('ok',200);
            }
        } else {
            return response('error', 500);
        }
    }
}
