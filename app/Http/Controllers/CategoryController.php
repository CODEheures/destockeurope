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
        $this->middleware('isAdminUser');
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
        $title = $request->title;
        $metaCategoryId = $request->metaCategoryId;
        if($title && $metaCategoryId && $title !='' && $metaCategoryId > 0) {
            $existMetaCategory = MetaCategory::find($metaCategoryId);
            if($existMetaCategory) {
                $existCategory = Category::where('meta_category_id', '=', $metaCategoryId)->where('title', 'LIKE', $title)->first();
                if($existCategory) {
                    return response(trans('strings.view_category_add_exist'), 409);
                } else {
                    $category = new Category;
                    $category->meta_category_id = $metaCategoryId;
                    $category->title = $title;
                    $category->save();
                    return response('ok',201);
                }
            } else {
                return response('error', 500);
            }
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
        $title = $request->title;
        if($id && $title && $id > 0 && $title !='') {
            $existCategory = Category::where('title', 'LIKE', $title)->first();
            if($existCategory) {
                return response(trans('strings.view_category_patch_exist'), 409);
            } else {
                $updateCategory = Category::find($id);
                if($updateCategory) {
                    $updateCategory->title = $title;
                    $updateCategory->save();
                    return response('ok',201);
                } else {
                    return response(trans('strings.view_category_patch_not_exist'), 409);
                }

            }
        } else {
            return response('error', 500);
        }
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
                $existCategory->delete();
                return response('ok',200);
            }
        } else {
            return response('error', 500);
        }
    }
}
