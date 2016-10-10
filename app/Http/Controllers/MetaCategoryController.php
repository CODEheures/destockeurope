<?php

namespace App\Http\Controllers;

use App\MetaCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class MetaCategoryController extends Controller
{

    public function __construct() {
        $this->middleware('isAdminUser');
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
        if($request->isXmlHttpRequest()) {
            return response()->json($metaCategories);
        }
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
        $title = $request->only('title');
        if($title && $title !='') {
            $existMetaCategory = MetaCategory::where('title', 'LIKE', $title)->first();
            if($existMetaCategory) {
                return response(trans('strings.view_category_add_exist'), 409);
            } else {
                MetaCategory::create($request->only('title'));
                return response('ok',201);
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
            $existMetaCategory = MetaCategory::where('title',  'LIKE', $title)->first();
            if($existMetaCategory) {
                return response(trans('strings.view_category_patch_exist'), 409);
            } else {
                $updateCategory = MetaCategory::find($id);
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
            $existMetaCategory = MetaCategory::find($id);
            if(!$existMetaCategory) {
                return response(trans('strings.view_category_del_not_exist'), 409);
            } else {
                $existMetaCategory->delete();
                return response('ok',200);
            }
        } else {
            return response('error', 500);
        }
    }
}
