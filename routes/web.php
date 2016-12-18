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
Route::get('/conditions-generales-de-vente', 'CommonController@cgv')->name('cgv');

// Admin Routes...
Route::group(['prefix' => 'admin'], function() {
    Route::get('/manage', 'AdminController@manage')->name('application.manage');
    Route::get('/dashboard', 'AdminController@dashboard')->name('application.dashboard');
    Route::get('/parameters', 'AdminController@appParameters')->name('application.parameters');
    Route::patch('/parameters', 'AdminController@patchParameters');
    Route::get('/get-welcome-list-type', 'AdminController@getWelcomeType')->name('advert.getWelcomeListType');
    Route::get('/cleanApp', 'AdminController@cleanApp')->name('application.cleanApp');
    Route::get('/transfertMedias/{sizeInMb?}', 'AdminController@transfertMedias')->name('application.transfertMedias');
    Route::get('/progressTransfertMedias', 'AdminController@progressTransfertMedias')->name('application.progressTransfertMedias');
    Route::get('/stats', 'AdminController@getStats')->name('application.getStats');
});

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
    Route::get('getMe', ['as' => 'user.getMe', 'uses' => 'UserController@getMe']);
    Route::get('account', ['as' => 'user.account', 'uses' => 'UserController@index']);
    Route::get('completeAccount/{id}', ['as' => 'user.completeAccount', 'uses' => 'UserController@completeAccount']);
    //User Preferences settings
    Route::patch('currency', ['as' => 'user.currency', 'uses' => 'UserController@setCurrency']);
    Route::patch('locale', ['as' => 'user.locale', 'uses' => 'UserController@setLocale']);
    Route::patch('location', ['as' => 'user.location', 'uses' => 'UserController@setLocation']);
    Route::patch('name', ['as' => 'user.name', 'uses' => 'UserController@setName']);
    Route::patch('phone', ['as' => 'user.phone', 'uses' => 'UserController@setPhone']);
    Route::patch('compagny-name', ['as' => 'user.compagnyName', 'uses' => 'UserController@setCompagnyName']);
    Route::patch('registration-number', ['as' => 'user.registrationNumber', 'uses' => 'UserController@setRegistrationNumber']);
});

//Utils
Route::group(['prefix' => 'utils'] , function () {
    Route::get('/get-list-currencies', ['as' => 'utils.getListCurrencies', 'uses' => 'UtilsController@getListCurrencies']);
    Route::get('/get-list-locales', ['as' => 'utils.getListLocales', 'uses' => 'UtilsController@getListLocales']);
    Route::post('/isPicture', 'UtilsController@isPicture')->name('utils.isPicture');
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
Route::get('advert/reviewForPayment/{id?}', ['as' => 'advert.reviewForPayment', 'uses' => 'AdvertController@reviewForPayment']);
Route::get('advert/get-list-type', ['as' => 'advert.getListType', 'uses' => 'AdvertController@getListType']);
Route::get('advert/toApprove', ['as' => 'advert.toApprove', 'uses' => 'AdvertController@toApprove']);
Route::get('advert/listApprove', ['as' => 'advert.listApprove', 'uses' => 'AdvertController@listApprove']);
Route::post('advert/approve', ['as' => 'advert.approve', 'uses' => 'AdvertController@approve']);
Route::get('advert/cost/{nbPictures?}/{isUrgent?}', ['as' => 'advert.cost', 'uses' => 'AdvertController@cost']);
Route::post('advert/sendMail', ['as' => 'advert.sendMail', 'uses' => 'AdvertController@sendMail']);
Route::get('advert/pay/paypal/{id}', ['as' => 'advert.payByPaypal', 'uses' => 'AdvertController@payByPaypal']);
Route::get('advert/pay/paypal/status/{id}/{success}', ['as' => 'advert.paypalStatus', 'uses' => 'AdvertController@paypalStatus'])
    ->where(['id' => '[0-9]+'])
    ->where(['success' => '\b(true|false)\b']);
Route::post('advert/pay/card/{id}', ['as' => 'advert.payByCard', 'uses' => 'AdvertController@payByCard']);
Route::resource('advert', 'AdvertController');

//Pictures
Route::post('picture', ['as' => 'picture.post', 'uses' => 'PictureController@post']);
Route::delete('picture/{hashName?}', ['as' => 'picture.destroyTempo', 'uses' => 'PictureController@destroyTempo']);
Route::get('picture/list-thumbs/{type}', ['as' => 'picture.listThumbs', 'uses' => 'PictureController@getListThumbs']);
Route::get('picture/thumb/{type}/{hashName?}/{advertId?}', ['as' => 'picture.thumb', 'uses' => 'PictureController@getThumb']);
Route::get('picture/normal/{hashName}/{advertId}', ['as' => 'picture.normal', 'uses' => 'PictureController@getNormal']);

//Bookmarks
Route::get('bookmark/add/{advertId?}', ['as' => 'bookmark.add', 'uses' => 'BookmarkController@add']);
Route::get('bookmark/remove/{advertId?}', ['as' => 'bookmark.remove', 'uses' => 'BookmarkController@remove']);


//DANGEROUS
Route::group(['prefix' => 'dangerous'] , function () {
    Route::get('/testGame', 'UtilsController@testGame');
    Route::get('/tempo', 'UtilsController@tempo');
});