<?php

namespace App\Common;


use App\Anonymous;
use App\User;

trait UserUtils
{
    public static function haveCompleteAccount($userparam=null) {
        if(auth()->check()) {
            if(!is_null($userparam) && PrivilegesUtils::canTestCompleteAccount()){
                $user = $userparam;
            } else {
                $user = auth()->user();
            }

            if(
                ($user->name != null
                    && $user->compagnyName != null
                    && strlen($user->name) >= config('db_limits.users.minName')
                    && strlen($user->compagnyName) >= config('db_limits.users.minCompagnyName')
                    && strlen($user->registrationNumber) >= config('db_limits.users.minRegistrationNumber'))
                || (is_null($userparam) && PrivilegesUtils::canBypassCompleteAccount())
            ) {
                return true;
            }
        }
        return false;
    }

    public static function createAnonymous($email, $name=null, $phone=null, $compagnyName=null, $isNewsLetterSubscriber=false) {
        $anonymous = Anonymous::create([
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
            'compagnyName' => $compagnyName,
            'isNewsLetterSubscriber' => $isNewsLetterSubscriber
        ]);
        $anonymous->save();
    }

    public static function updateAnonymous(Anonymous $anonymous, $name=null, $phone=null, $compagnyName=null, $isNewsLetterSubscriber=false) {
        !is_null($name) && $name!='' ? $anonymous->name = $name : null;
        !is_null($phone) && $phone!='' ? $anonymous->phone = $phone : null;
        !is_null($compagnyName) && $compagnyName!='' ? $anonymous->compagnyName = $compagnyName : null;
        $anonymous->isNewsLetterSubscriber = $isNewsLetterSubscriber;
        $anonymous->save();
    }

    public static function createOrUpdateAnonymous($email, $name=null, $phone=null, $compagnyName=null, $isNewsLetterSubscriber=false) {
        $existAnonymous = Anonymous::whereMail($email)->first();
        if(!$existAnonymous){
            self::createAnonymous($email, $name, $phone, $compagnyName, $isNewsLetterSubscriber);
        } else {
            self::updateAnonymous($existAnonymous, $name, $phone, $compagnyName, $isNewsLetterSubscriber);
        }
    }
    /**
     *
     * Return array with firstName and lastName
     *
     * @param $name
     * @return array
     */
    public static function splitName($name) {
        $result = (explode(' ', $name));
        $firstname = '';
        $lastname = '';
        for($i=0; $i<count($result); $i++) {
            $i < count($result)/2 ? $firstname .= ' ' . $result[$i]: $lastname .= ' ' . $result[$i];
        }
        return [
            'firstName' => substr($firstname,1),
            'lastName' => substr($lastname,1),
        ];
    }
}