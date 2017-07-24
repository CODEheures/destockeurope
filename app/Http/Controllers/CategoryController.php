<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Category;
use App\Common\CategoryUtils;
use App\Common\PrivilegesUtils;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    use CategoryUtils;

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('isAdminUser', ['except' => ['index', 'show']]);
    }

    public function manage() {
        return view('category.manage');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::defaultOrder()->get();
        if($request->has('withInfos') && $request->get('withInfos')=='true' && PrivilegesUtils::canAdmin()){
            foreach ($categories as $category){
                $category->setCanBeDeleted($this->availableToDelete($category->id));
                $category->setAvailableMoveTo();
            }
        }
        $tree = $categories->toTree();
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
        $parent_id = (int) $request->parentId;

        //1 test langages in lang
        foreach ($descriptions as $lang=>$description){
            if(!in_array($lang,config('codeheuresUtils.availableLocales'))){
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
            if(!in_array($lang,config('codeheuresUtils.availableLocales'))){
                return response('error', 500);
            }
        }

        //2 test exist Categories
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
                DB::beginTransaction();
                $ids = CategoryUtils::getListSubTree($existCategory->id);
                if($ids){
                    $adverts = Advert::whereIn('category_id', $ids)->get();
                    foreach ($adverts as $advert){
                        $advert->delete();
                    }
                }
                $existCategory->delete();
                DB::commit();
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
        $id = (int) $request->id;
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
        $id = (int) $request->id;
        if($id && $id > 0 ) {
            $existCategory = Category::find($id);
            if(!$existCategory) {
                return response(trans('strings.view_category_patch_not_exist'), 409);
            } else {
                $result = $existCategory->down();
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function availableMoveTo($id)
    {
        if($id && $id > 0 ) {
            $category = Category::find($id);
            if($category){
                $tree = Category::whereNotDescendantOf($category)->defaultOrder()->get();
                foreach ($tree as $key => $item){
                    if($item->id == $category->id){
                        $tree->pull($key);
                    }
                }

                if(!$category->isRoot()){
                    $descriptions = [];
                    foreach (config('codeheuresUtils.availableLocales') as $lang){
                        $descriptions[$lang] = trans('strings.form_dropdown_move_as_root',[],'',$lang);
                    }
                    $cat = new Category();
                    $cat->description = $descriptions;
                    $cat->id=-1;
                    $tree->prepend($cat);
                }

                return response()->json($tree->toTree());
            } else {
                return response(trans('strings.view_category_patch_not_exist'), 409);
            }
        } else {
            return response('error', 500);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return bool
     */
    private function availableToDelete($id)
    {
        if($id && $id > 0 ) {
            $category = Category::find($id);
            $nbSubAdverts=0;
            if($category) {
                $ids = CategoryUtils::getListSubTree($category->id);
                if ($ids) {
                    $nbSubAdverts = Advert::withTrashed()->whereIn('category_id', $ids)->count();
                }
            }
            return !$nbSubAdverts;
        } else {
            return false;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function appendTo(Request $request)
    {
        $childId = (int) $request->childId;
        $parentId = (int) $request->parentId;
        if($childId && $parentId && $childId > 0 && ($parentId > 0 || $parentId==-1)) {
            $existChildCategory = Category::find($childId);

            if($parentId > 0){
                $existParentCategory = Category::find($parentId);
                if(!$existChildCategory || !$existParentCategory) {
                    return response(trans('strings.view_category_patch_not_exist'), 409);
                } elseif ($existChildCategory && $existChildCategory->isChildOf($existParentCategory)) {
                    return response(trans('strings.form_dropdown_move_already_child_of'), 409);
                } else {
                    $existChildCategory->appendToNode($existParentCategory);
                    $result = $existChildCategory->save();
                    if($result){
                        return response('ok',200);
                    } else {
                        return response(trans('strings.view_category_patch_not_exist'), 409);
                    }
                }
            } else {
                if(!$existChildCategory) {
                    return response(trans('strings.view_category_patch_not_exist'), 409);
                } elseif ($existChildCategory && $existChildCategory->isRoot()){
                    return response('error', 500);
                } else {
                    $result = $existChildCategory->saveAsRoot();
                    if($result){
                        return response('ok',200);
                    } else {
                        return response(trans('strings.view_category_patch_not_exist'), 409);
                    }
                }
            }
        } else {
            return response('error', 500);
        }
    }
}
