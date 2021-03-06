<?php

namespace App\Common;

use App\Category;

trait CategoryUtils
{
    public static function getListSubTree($id) {
        $categories = Category::descendantsWith($id)->get()->toFlatTree();
        if(count($categories)==1){
            $category = $categories[0];
            $ids = [];
            $ids[] = $category->id;
            foreach ($category->descendants as $descendant){
                $ids[] = $descendant->id;
            }
            return $ids;
        } else {
            return false;
        }
    }

    public static function getAllCategories() {
        $categories = Category::defaultOrder()->get();
        return $categories->toTree();
    }

}