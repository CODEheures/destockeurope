@extends('layouts.app')

@section('opengraph')
    <meta property="fb:app_id" content="{{ env('FACEBOOK_CLIENT_ID') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $advert->title . ' ' . trans('strings.global_for') . ' ' . $advert->price_margin}}">
    <meta property="og:description" content="{{ $advert->resume }}">
    <meta property="og:image" content="{{ $advert->thumb }}">
    <meta property="og:url" content="{{ $advert->url }}">
    <meta property="og:type" content="product.item">
    <meta property="product:retailer_item_id" content="{{ $advert->user->compagnyName }}">
    <meta property="product:price:amount" content="{{ \App\Common\MoneyUtils::getPriceWithDecimal($advert->originalPriceWithMargin,$advert->currency,false) }}">
    <meta property="product:price:currency"   content="{{ $advert->currency }}" />
    <meta property="product:availability"     content="in stock" />
    <meta property="product:condition"        content="new" />
@endsection

@section('content')

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

            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            send-success-message="{{ trans('strings.view_advert_show_message_send') }}"
            send-success-report-message="{{ trans('strings.view_advert_show_report_send') }}"
            form-validation-email="{{ trans('strings.form_validation_email') }}"
            form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimum_chars') }}"
            form-pointing-maximum-chars="{{ trans('strings.form_pointing_maximum_chars') }}"
            contact-label="{{ trans('strings.view_advert_show_contact_label') }}"
            report-label="{{ trans('strings.view_advert_report_label') }}"
            bookmark-info="{{ trans('strings.view_advert_show_bookmark_info') }}"
            bookmark-label="{{ trans('strings.view_advert_show_bookmark_label') }}"
            unbookmark-label="{{ trans('strings.view_advert_show_unbookmark_label') }}"
            manage-advert-label="{{ trans('strings.view_advert_show_manage_label') }}"
            renew-advert-label="{{ trans('strings.view_advert_show_renew_label') }}"
            back-to-top-advert-label="{{ trans('strings.view_advert_show_backToTop_label') }}"
            highlight-advert-label="{{ trans('strings.view_advert_show_highlight_label') }}"
            delete-advert-label="{{ trans('strings.view_advert_show_delete2_label') }}"
            see-advert-label="{{ trans('strings.view_advert_show_see_label') }}"
            edit-advert-label="{{ trans('strings.view_advert_show_edit_label') }}"
            see-advert-popup-label="{{ trans('strings.view_advert_show_see_popup_label') }}"
            edit-advert-popup-label="{{ trans('strings.view_advert_show_edit_popup_label') }}"
            delete-advert-popup-label="{{ trans('strings.view_advert_show_delete2_popup_label') }}"
            back-to-top-popup-label="{{ trans('strings.view_advert_show_backToTop_popup_label') }}"
            highlight-popup-label="{{ trans('strings.view_advert_show_highlight_popup_label') }}"
            renew-advert-popup-label="{{ trans('strings.view_advert_show_renew_popup_label') }}"
            form-message-label="{{ trans('strings.form_label_message_input') }}"
            form-message-email-label="{{ trans('strings.form_label_email') }}"
            form-message-name-label="{{ trans('strings.form_label_name') }}"
            form-message-phone-label="{{ trans('strings.form_label_phone') }}"
            form-message-compagny-label="{{ trans('strings.view_user_account_compagny_name_label') }}"
            form-see-phone-label="{{ trans('strings.form_see_phone_label') }}"
            form-message-send-label="{{ trans('strings.modal_send') }}"
            form-message-cancel-label="{{ trans('strings.modal_cancel') }}"
            bookmark-success="{{ trans('strings.view_advert_show_bookmark_success') }}"
            unbookmark-success="{{ trans('strings.view_advert_show_unbookmark_success') }}"
            modal-valid-header="{{ trans('strings.view_advert_show_modal_delete_header') }}"
            modal-valid-description="{{ trans('strings.view_advert_show_modal_delete_description') }}"
            modal-no="{{ trans('strings.modal_no') }}"
            modal-yes="{{ trans('strings.modal_yes') }}"
            all-label="{{ trans('strings.view_all_all') }}"

            route-home="{{ route('home') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
            image-ratio="{{ env('IMAGE_RATIO') }}"
            total-quantity-label="{{ trans('strings.form_quantity_label') }}"
            lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
            urgent-label="{{ trans('strings.view_all_urgent') }}"
            is-negociated-label="{{ trans('strings.view_all_negociate') }}"
            price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
            price-label="{{ trans('strings.view_advert_form_price_label') }}"
            ref-label="{{ trans('strings.view_advert_form_ref_label') }}"
    ></show-advert1>

@endsection

@section('scripts')
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '{{ env('FACEBOOK_CLIENT_ID') }}',
                xfbml      : true,
                version    : 'v2.8'
            });
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/fr_FR/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
@endsection