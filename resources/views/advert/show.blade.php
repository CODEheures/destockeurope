@extends('layouts.app')

@section('titlePagePlus')
{{ $advert->id % 2 == 0 ? trans('strings.view_advert_show_title_1', ['title'=> $advert->title]) :  trans('strings.view_advert_show_title_2', ['title'=> $advert->title]) }}
@endsection

@section('opengraph')
    <meta property="fb:app_id" content="{{ env('FACEBOOK_CLIENT_ID') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $advert->title . ' ' . trans('strings.global_for') . ' ' . $advert->price_margin}}">
    <meta property="og:description" content="{{ $advert->resume }}">
    <meta property="og:image" content="{{ $advert->thumb }}">
    <meta property="og:url" content="{{ route('advert.show', ['slug' => $advert->slug, 'lang' => \Illuminate\Support\Facades\App::getLocale()]) }}">
    <meta property="og:type" content="product.item">
    <meta property="product:retailer_item_id" content="{{ $advert->user->compagnyName }}">
    <meta property="product:price:amount" content="{{ \App\Common\MoneyUtils::getPriceWithDecimal($advert->originalPriceWithMargin,$advert->currency,false) }}">
    <meta property="product:price:currency"   content="{{ $advert->currency }}" />
    <meta property="product:availability"     content="in stock" />
    <meta property="product:condition"        content="new" />
@endsection

@section('content')
    @include('storeSetter.strings.contents.showAdvert1')
    <show-advert1
            route-send-mail="{{ route('advert.sendMail') }}"
            route-bookmark-add="{{ route('bookmark.add', ['advertId'=>$advert->id]) }}"
            route-bookmark-remove="{{ route('bookmark.remove', ['advertId'=>$advert->id]) }}"
            route-delete-advert="{{ route('advert.destroy', ['advertId'=>$advert->id] ) }}"
            route-report-advert="{{ route('advert.report') }}"

            advert="{{ json_encode($advert) }}"
            user-mail="{{ auth()->check() ? auth()->user()->email : '' }}"
            user-name="{{ auth()->check() ? auth()->user()->name : '' }}"
            user-phone="{{ auth()->check() ? auth()->user()->phone : '' }}"
            user-compagny-name="{{ auth()->check() ? auth()->user()->compagnyName : '' }}"
            is-user-owner="{{ \App\Common\PrivilegesUtils::canEditAdvert($advert) ? true : false }}"
            is-user-bookmark="{{ auth()->check() ? auth()->user()->haveBookmark($advert->id) : false }}"
            form-name-min-valid="{{ config('db_limits.users.minName') }}"
            form-message-min-valid="{{ config('db_limits.messages.minLength') }}"
            form-message-max-valid="{{ config('db_limits.messages.maxLength') }}"
            form-phone-max-valid="{{ config('db_limits.users.maxPhone') }}"
            form-compagny-name-max-valid="{{ config('db_limits.users.maxCompagnyName') }}"
    ></show-advert1>

@endsection

@section('scripts')
    @include('plugins.facebook.sharer')
@endsection