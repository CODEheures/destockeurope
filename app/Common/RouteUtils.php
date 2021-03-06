<?php

namespace App\Common;


trait RouteUtils
{
    /**
     *
     * return if currentRoute have {lang} param
     * @return bool
     */
    public static function routeHaveLangParam() {
        if(\Illuminate\Support\Facades\Route::current()){
            return (in_array('lang', \Illuminate\Support\Facades\Route::current()->parameterNames));
        }
        return false;
    }
}