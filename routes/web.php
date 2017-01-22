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


Route::group(['middleware' => 'fw-allow-wl'], function () {
    //Common
    Route::get('/', 'CommonController@portal')->name('portal');
    Route::get('/home', 'CommonController@home')->name('home');
    Route::post('/subscribeNewsLetter', 'CommonController@subscribeNewsLetter')->name('subscribeNewsLetter');
    Route::get('/mines', ['as' => 'mines', 'uses' => 'CommonController@mines']);
    Route::get('/bookmarks', ['as' => 'bookmarks', 'uses' => 'CommonController@bookmarks']);
    Route::get('/conditions-generales-de-vente', 'CommonController@cgv')->name('cgv');

    // Admin Routes...
    Route::group(['prefix' => 'admin'], function() {
        Route::get('/manage', 'AdminController@manage')->name('application.manage');
        Route::get('/delegations', 'AdminController@delegations')->name('advert.delegations');
        Route::get('/dashboard', 'AdminController@dashboard')->name('application.dashboard');
        Route::get('/parameters', 'AdminController@appParameters')->name('application.parameters');
        Route::patch('/parameters', 'AdminController@patchParameters');
        Route::get('/get-welcome-list-type', 'AdminController@getWelcomeType')->name('advert.getWelcomeListType');
        Route::get('/cleanApp', 'AdminController@cleanApp')->name('application.cleanApp');
        Route::get('/transfertMedias/{sizeInMb?}', 'AdminController@transfertMedias')->name('application.transfertMedias');
        Route::get('/progressTransfertMedias', 'AdminController@progressTransfertMedias')->name('application.progressTransfertMedias');
        Route::get('/stats', 'AdminController@getStats')->name('application.getStats');
        Route::get('/testLangs', 'AdminController@testLangs');
    });

    //Auth::routes();
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');

    // Registration Routes...
    Route::group(['prefix' => 'register'], function() {
        Route::get('/', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('/', 'Auth\RegisterController@register');
    });

    // Password Reset Routes...
    Route::group(['prefix' => 'password'], function() {
        Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
        Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
        Route::post('/reset', 'Auth\ResetPasswordController@reset');
    });


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
        Route::get('completeAccount/{id}/{title?}', ['as' => 'user.completeAccount', 'uses' => 'UserController@completeAccount']);
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
    Route::group(['prefix' => 'category'] , function () {
        Route::get('/manage',  ['as' => 'category.manage', 'uses' =>'CategoryController@manage']);
        Route::patch('/shiftUp',  ['as' => 'category.shiftUp', 'uses' =>'CategoryController@shiftUp']);
        Route::patch('/shiftDown',  ['as' => 'category.shiftDown', 'uses' =>'CategoryController@shiftDown']);
        Route::get('/available-move-to/{id?}',  ['as' => 'category.availableMoveTo', 'uses' =>'CategoryController@availableMoveTo']);
        Route::patch('/append-to',  ['as' => 'category.appendTo', 'uses' =>'CategoryController@appendTo']);
    });
    Route::resource('category', 'CategoryController');

    //Adverts
    Route::group(['prefix' => 'advert'] , function () {
        Route::get('/publish/{id?}', ['as' => 'advert.publish', 'uses' => 'AdvertController@publish']);
        Route::get('/reviewForPayment/{id?}', ['as' => 'advert.reviewForPayment', 'uses' => 'AdvertController@reviewForPayment']);
        Route::get('/get-list-type', ['as' => 'advert.getListType', 'uses' => 'AdvertController@getListType']);
        Route::get('/toApprove', ['as' => 'advert.toApprove', 'uses' => 'AdvertController@toApprove']);
        Route::get('/listApprove', ['as' => 'advert.listApprove', 'uses' => 'AdvertController@listApprove']);
        Route::post('/approve', ['as' => 'advert.approve', 'uses' => 'AdvertController@approve']);
        Route::get('/delegations', ['as' => 'advert.getDelegations', 'uses' => 'AdvertController@delegations']);
        Route::patch('/updateCoefficient/{id?}/{coefficient?}', ['as' => 'advert.updateCoefficient', 'uses' => 'AdvertController@updateCoefficient']);
        Route::get('/cost/{nbPictures?}/{isUrgent?}', ['as' => 'advert.cost', 'uses' => 'AdvertController@cost']);
        Route::post('/sendMail', ['as' => 'advert.sendMail', 'uses' => 'AdvertController@sendMail']);
        Route::post('/report', ['as' => 'advert.report', 'uses' => 'AdvertController@report']);
        Route::get('/pay/paypal/{id}', ['as' => 'advert.payByPaypal', 'uses' => 'AdvertController@payByPaypal']);
        Route::get('/pay/paypal/status/{id}/{success}', ['as' => 'advert.paypalStatus', 'uses' => 'AdvertController@paypalStatus'])
            ->where(['id' => '[0-9]+'])
            ->where(['success' => '\b(true|false)\b']);
        Route::post('/pay/card/{id}', ['as' => 'advert.payByCard', 'uses' => 'AdvertController@payByCard']);
        Route::get('/mines', ['as' => 'advert.mines', 'uses' => 'AdvertController@mines']);
        Route::get('/bookmarks', ['as' => 'advert.bookmarks', 'uses' => 'AdvertController@bookmarks']);
        Route::get('/renew/{id}', ['as' => 'advert.renew', 'uses' => 'AdvertController@renew']);
    });
    Route::resource('advert', 'AdvertController');

    Route::group(['prefix' => 'videos'] , function () {
        Route::put('/ticket', ['as' => 'videos.ticket', 'uses' => 'VideoController@ticket']);
        Route::patch('/ticket', ['as' => 'videos.closeTicket', 'uses' => 'VideoController@closeTicket']);
        Route::delete('/tempo/', ['as' => 'videos.delTempo', 'uses' => 'VideoController@delTempoVideo']);
        Route::get('/status/', ['as' => 'videos.status', 'uses' => 'VideoController@status']);
    });

    //Pictures
    Route::group(['prefix' => 'picture'] , function () {
        Route::post('/', ['as' => 'picture.post', 'uses' => 'PictureController@post']);
        Route::delete('/{hashName?}', ['as' => 'picture.destroyTempo', 'uses' => 'PictureController@destroyTempo']);
        Route::get('/list-thumbs/{type}', ['as' => 'picture.listThumbs', 'uses' => 'PictureController@getListThumbs']);
        Route::get('/thumb/{type}/{hashName?}/{advertId?}', ['as' => 'picture.thumb', 'uses' => 'PictureController@getThumb']);
        Route::get('/normal/{hashName}/{advertId}', ['as' => 'picture.normal', 'uses' => 'PictureController@getNormal']);
    });


    //Bookmarks
    Route::group(['prefix' => 'bookmark'] , function () {
        Route::get('/add/{advertId?}', ['as' => 'bookmark.add', 'uses' => 'BookmarkController@add']);
        Route::get('/remove/{advertId?}', ['as' => 'bookmark.remove', 'uses' => 'BookmarkController@remove']);
    });
    //DANGEROUS
    Route::group(['prefix' => 'dangerous'] , function () {
        Route::get('/testGame', 'UtilsController@testGame');
        Route::get('/tempo', 'UtilsController@tempo');
    });

});