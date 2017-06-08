<?php

namespace App\Common;


use App\Advert;
use App\Invoice;
use App\User;

trait PrivilegesUtils
{
    //Globals Privileges
    public static function canAdmin() {
        return
            auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN];
    }

    public static function canManageMyAccount() {
        return
            auth()->check() && auth()->user()->role != User::ROLES[User::ROLE_SUPPLIER];
    }

    public static function  canBypassCompleteAccount() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_VALIDATOR]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canTestCompleteAccount() {
        return
            auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN];
    }

    public static function canPatchRoleUser(User $user) {
        return
            auth()->check()
            && auth()->user()->role == User::ROLES[User::ROLE_ADMIN]
            && $user->id != auth()->user()->id;
    }

    public static function canCreate() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER]
                || auth()->user()->role == User::ROLES[User::ROLE_SUPPLIER]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canDestroy() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER]
                || auth()->user()->role == User::ROLES[User::ROLE_SUPPLIER]
                || auth()->user()->role == User::ROLES[User::ROLE_VALIDATOR]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canGetMines() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER]
                || auth()->user()->role == User::ROLES[User::ROLE_SUPPLIER]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canGetDelegations() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_VALIDATOR]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canGetBookmarks() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER]
                || auth()->user()->role == User::ROLES[User::ROLE_SUPPLIER]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canApprove() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_VALIDATOR]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canEdit() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER] ||
                auth()->user()->role == User::ROLES[User::ROLE_SUPPLIER]
                ||auth()->user()->role == User::ROLES[User::ROLE_VALIDATOR]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canUpdateCoefficient() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_ADMIN]
                || auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canUpdateQuantities() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER]
                || auth()->user()->role == User::ROLES[User::ROLE_SUPPLIER]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]
                || auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canRefund() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_VALIDATOR]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canRenew() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]
                || auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canBackToTop() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]
                || auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canHighlight() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_USER]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]
                || auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canManageInvoices() {
        return
            auth()->check()
            && (auth()->user()->role == User::ROLES[User::ROLE_VALIDATOR]
                || auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canManageApplication() {
        return
            auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN];
    }

    public static function canTransformAdvertToDelegation() {
        return auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_SUPPLIER];
    }

    public static function canInvalidOnDeleting() {
        return auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN];
    }
    //Adverts Privileges
    public static function canPublishAdvert(Advert $advert) {
        return
            $advert->isUserOwner;
    }

    public static function canDestroyAdvert(Advert $advert) {
        return
            $advert->isUserOwner
            || (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN])
            || ($advert->is_delegation && auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canEditAdvert(Advert $advert) {
        return
            $advert->isUserOwner
            || (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN])
            || ($advert->is_delegation && auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canUpdateQuantitiesAdvert(Advert $advert) {
        return
            $advert->isUserOwner
            || (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN])
            || ($advert->is_delegation && auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canUpdateCoefficientAdvert(Advert $advert) {
        return
            (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN])
            || ($advert->is_delegation && auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canHighlightAdvert(Advert $advert) {
        return
            (!$advert->is_delegation && $advert->isUserOwner)
            || (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN])
            || ($advert->is_delegation && auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canBackToTopAdvert(Advert $advert) {
        return
            (!$advert->is_delegation && $advert->isUserOwner)
            || (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN])
            || ($advert->is_delegation && auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }

    public static function canRenewAdvert(Advert $advert) {
        return
            !$advert->is_delegation
            && ($advert->isUserOwner
                ||(auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN])
                ||(auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]));
    }

    public static function canShowXmlAdvert(Advert $advert) {
        return
            $advert->isUserOwner
            || (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN]);
    }

    public static function canReviewInvoice(Invoice $invoice) {
        return $invoice->isUserOwner;
    }

    //Mailings Privileges
    public static function canReceiveCreateInvoicePdfFail() {
        return [
            User::ROLES[User::ROLE_ADMIN],
        ];
    }

    public static function canReceiveBypassAdvertMessage() {
        return [
            User::ROLES[User::ROLE_INTERMEDIARY],
        ];
    }

    public static function canReceiveContact() {
        return [
            User::ROLES[User::ROLE_ADMIN],
        ];
    }

    public static function canReceiveReport() {
        return [
            User::ROLES[User::ROLE_ADMIN],
        ];
    }

    public static function canReceiveError() {
        return [
            User::ROLES[User::ROLE_ADMIN],
        ];
    }

    public static function canReceiveInvoice() {
        return [
            User::ROLES[User::ROLE_ACCOUNTANT],
        ];
    }

    public static function canReceiveBypassAutoprocessNotify($advert) {
        return $advert->user->role!=User::ROLES[User::ROLE_VALIDATOR]
            && $advert->user->role!=User::ROLES[User::ROLE_ADMIN];
    }

    //Globals obligations
    public static function mustBeCompleteToBePatched() {
        return [
            User::ROLES[User::ROLE_SUPPLIER]
        ];
    }


    //Cost privileges
    public static function isCostFree() {
        return
            (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_SUPPLIER])
            || (auth()->check() && auth()->user()->role == User::ROLES[User::ROLE_ADMIN])
            || (auth()->check() && auth()->user()->role==User::ROLES[User::ROLE_VALIDATOR]);
    }
}