<?php

namespace App\Common;


trait UserUtils
{
    public static function haveCompleteAccount() {
        if(auth()->check()) {
            $user = auth()->user();
            if(
                $user->name != null
                && $user->compagnyName != null
                && $user->registrationNumber != null
                && strlen($user->name) >= config('db_limits.users.minName')
                && strlen($user->compagnyName) >= config('db_limits.users.minCompagnyName')
                && strlen($user->registrationNumber) >= config('db_limits.users.minRegistrationNumber')
            ) {
                return true;
            }
        }
        return false;
    }
}