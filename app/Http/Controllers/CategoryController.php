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

    public function manage() {
        return view('category.manage');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tree = Category::defaultOrder()->get()->toTree();
        return response()->json($tree);
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
        $parent_id = $request->parentId;

        //1 test langages in lang
        foreach ($descriptions as $lang=>$description){
            if(!in_array($lang,config('app.locales'))){
                return response('error', 500);
            }
        }

        //2 test if parent exist
        $parent = null;
        if($parent_id && $parent_id > 0) {
            $parent = Category::withDepth()->find($parent_id);
            if(!$parent) {
                return response(trans('strings.view_category_add_parent_not_exist',409));
            }
        }

        //3 test if category exist
        $listOfNodeTest = new Collection();
        if($parent){
            $descendants = $parent->descendants()->get();
            foreach ($descendants as $descendant) {
                if ($descendant->isChildOf($parent)) {
                    $listOfNodeTest->add($descendant);
                }
            }
        } else {
            $listOfNodeTest = Category::whereIsRoot()->get();
        }

        foreach ($listOfNodeTest as $nodeTest){
            foreach ($descriptions as $lang => $description) {
                if ($nodeTest->description[$lang] == $description) {
                    return response(trans('strings.view_category_add_exist'), 409);
                }
            }
        }


        //Finaly
        $cat = new Category();
        $cat->description = $descriptions;
        if($parent_id && $parent) {
            $parent->appendNode($cat);
        } else {
            $cat->saveAsRoot();
        }
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
        $category = Category::find($id);
        if($category){
            $ancestors = $category->getAncestors();
            $ancestors->add($category);
            return response()->json($ancestors);
        }
        return response()->json([]);
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
                $existCategory->delete();
                return response('ok',200);
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
    public function shiftUp(Request $request)
    {
        $id = $request->id;
        if($id && $id > 0 ) {
            $existCategory = Category::find($id);
            if(!$existCategory) {
                return response(trans('strings.view_category_patch_not_exist'), 409);
            } else {
                $result = $existCategory->up();
                if($result){
                    return response('ok',200);
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
    public function shiftDown(Request $request)
    {
        $id = $request->id;
        if($id && $id > 0 ) {
            $existCategory = Category::find($id);
            if(!$existCategory) {
                return response(trans('strings.view_category_patch_not_exist'), 409);
            } else {
                $result = $existCategory->down();
                if($result){
                    return response('ok',200);
                } else {
                    //return response(trans('strings.view_category_patch_not_exist'), 409);
                    return response(trans('crotte'), 409);
                }
            }
        } else {
            return response('error', 500);
        }
    }

    public function fixTree()
    {
        Category::fixTree();
        return back();
    }
}
