<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Common
Route::get('/', 'CommonController@home')->name('home');
Route::get('/manage', 'CommonController@manage')->name('application.manage');
Route::get('/u15t6rs1hqe6h1qreh16er561he5r', 'CommonController@appParameters')->name('appParameters');
Route::patch('/u15t6rs1hqe6h1qreh16er561he5r', 'CommonController@patchParameters');
Route::get('/u15t6rs1hqe6h1qreh16er561he5r/get-welcome-list-type', 'CommonController@getWelcomeType')->name('advert.getWelcomeListType');


//Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
    Route::get('social/{provider}', ['as' => 'social.login', 'uses' => 'SocialiteController@redirectToProvider'])
        ->where(['provider' => '[a-zA-Z]+']);
    Route::get('social/callback/{provider}', ['as' => 'social.callback', 'uses' => 'SocialiteController@handleProviderCallback'])
        ->where(['provider' => '[a-zA-Z]+']);

    //Account confirmation
    Route::get('account-confirm/{id}/{token}', ['as' => 'account.confirm', 'uses' => 'RegisterController@accountConfirm']);
});

Route::group(['prefix' => 'users'], function() {
    Route::get('account', ['as' => 'user.account', 'uses' => 'UserController@index']);
    Route::get('completeAccount/{id}', ['as' => 'user.completeAccount', 'uses' => 'UserController@completeAccount']);
    //User Preferences settings
    Route::patch('currency', ['as' => 'user.currency', 'uses' => 'UserController@setCurrency']);
    Route::patch('locale', ['as' => 'user.locale', 'uses' => 'UserController@setLocale']);
    Route::patch('location', ['as' => 'user.location', 'uses' => 'UserController@setLocation']);
    Route::patch('name', ['as' => 'user.name', 'uses' => 'UserController@setName']);
    Route::patch('compagny-name', ['as' => 'user.compagnyName', 'uses' => 'UserController@setCompagnyName']);
    Route::patch('registration-number', ['as' => 'user.registrationNumber', 'uses' => 'UserController@setRegistrationNumber']);
});

//Utils

Route::group(['prefix' => 'utils'] , function () {
    Route::get('/get-list-currencies', ['as' => 'utils.getListCurrencies', 'uses' => 'UtilsController@getListCurrencies']);
    Route::get('/get-list-locales', ['as' => 'utils.getListLocales', 'uses' => 'UtilsController@getListLocales']);
    Route::get('/testGame', 'UtilsController@testGame');
    Route::post('/isPicture', 'UtilsController@isPicture')->name('utils.isPicture');
    Route::get('/tempo', 'UtilsController@tempo');
});

//Categories
Route::get('categories/manage',  ['as' => 'category.manage', 'uses' =>'CategoryController@manage']);
Route::patch('category/shiftUp',  ['as' => 'category.shiftUp', 'uses' =>'CategoryController@shiftUp']);
Route::patch('category/shiftDown',  ['as' => 'category.shiftDown', 'uses' =>'CategoryController@shiftDown']);
Route::get('category/available-move-to/{id?}',  ['as' => 'category.availableMoveTo', 'uses' =>'CategoryController@availableMoveTo']);
Route::patch('category/append-to',  ['as' => 'category.appendTo', 'uses' =>'CategoryController@appendTo']);
Route::resource('category', 'CategoryController');

//Adverts
Route::get('advert/publish/{id?}', ['as' => 'advert.publish', 'uses' => 'AdvertController@publish']);
Route::get('advert/get-list-type', ['as' => 'advert.getListType', 'uses' => 'AdvertController@getListType']);
Route::get('advert/toApprove', ['as' => 'advert.toApprove', 'uses' => 'AdvertController@toApprove']);
Route::get('advert/listApprove', ['as' => 'advert.listApprove', 'uses' => 'AdvertController@listApprove']);
Route::post('advert/approve', ['as' => 'advert.approve', 'uses' => 'AdvertController@approve']);
Route::get('advert/cost/{nbPictures?}/{isUrgent?}', ['as' => 'advert.cost', 'uses' => 'AdvertController@cost']);
Route::post('advert/sendMail', ['as' => 'advert.sendMail', 'uses' => 'AdvertController@sendMail']);
Route::resource('advert', 'AdvertController');

//Pictures
Route::post('picture', ['as' => 'picture.post', 'uses' => 'PictureController@post']);
Route::delete('picture/{type}/{hashName?}', ['as' => 'picture.destroy', 'uses' => 'PictureController@destroy']);
Route::get('picture/list-thumbs/{type}', ['as' => 'picture.listThumbs', 'uses' => 'PictureController@getListThumbs']);
Route::get('picture/thumb/{type}/{hashName?}/{advertId?}', ['as' => 'picture.thumb', 'uses' => 'PictureController@getThumb']);
Route::get('picture/normal/{hashName}/{advertId}', ['as' => 'picture.normal', 'uses' => 'PictureController@getNormal']);