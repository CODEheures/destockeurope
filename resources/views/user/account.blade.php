@extends('layouts.app')

@section('content')
   @include('storeSetter.strings.contents.user-account')
    <user-account
            route-user-get-me="{{ route('user.getMe') }}"
            route-change-email="{{ route('changeEmail', ['email' => auth()->user()->email]) }}"
            route-change-password="{{ route('password.change') }}"
            route-user-set-pref-currency="{{ route('user.currency') }}"
            route-user-set-pref-locale="{{ route('user.locale') }}"
            route-user-set-pref-location="{{ route('user.location') }}"
            route-user-set-name="{{ route('user.name') }}"
            route-user-set-phone="{{ route('user.phone') }}"
            route-user-set-compagny-name="{{ route('user.compagnyName') }}"
            route-user-set-registration-number="{{ route('user.registrationNumber') }}"
            route-avatar="{{ $routeAvatar }}"
            route-next-url="{{ isset($advert) ? route('advert.nextStep', ['id' => $advert->id]) : '' }}"
            route-prices="{{ route('prices') }}"

            user-email="{{ $user->email }}"
            user-name="{{ $user->name }}"
            user-phone="{{ $user->phone }}"
            latitude="{{ $user->latitude }}"
            longitude="{{ $user->longitude }}"
            first-geoloc="{{ $user->geoloc == null ? true : false }}"
            compagny-name="{{ $user->compagnyName }}"
            registration-number="{{ $user->registrationNumber }}"
            vat-identifier="{{ $user->vatIdentifier }}"
            advert-account-verified-step="{{ isset($advertAccountVerifiedStep) ? $advertAccountVerifiedStep : false }}"
            advert-cost="{{ isset($cost) ? $cost : '0' }}"
            form-phone-max-valid="{{ config('db_limits.users.maxPhone') }}"
            form-compagny-name-min-valid="{{ config('db_limits.users.minCompagnyName') }}"
            form-compagny-name-max-valid="{{ config('db_limits.users.maxCompagnyName') }}"
            form-registration-number-max-valid="{{ config('db_limits.users.maxRegistrationNumber') }}"
    ></user-account>

@endsection

@section('scripts')
    @include('plugins.googleMap.map.script')
@endsection