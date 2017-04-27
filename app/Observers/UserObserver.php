<?php

namespace App\Observers;

use App\Common\UserUtils;
use App\User;

class UserObserver
{
    public function created(User $user) {
        if(session()->has('subscribeNewsLetter') && session('subscribeNewsLetter')){
            UserUtils::createOrUpdateAnonymous($user->email, $user->name, null, null, true);
            session()->forget('subscribeNewsLetter');
        }
    }
}