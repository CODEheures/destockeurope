<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facebook_id',
        'google_id',
        'twitter_id',
        'github_id',
        'linkedin_id',
        'name',
        'email',
        'password',
        'confirmed',
        'confirmationToken',
        'avatar',
        'currency',
        'locale',
        'compagnyName',
        'registrationNumber',
        'latitude',
        'longitude',
        'geoloc'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role'
    ];

    public function oAuthProvider($providers) {
        $refOauth = '';
        foreach ($providers as $testId) {
            $column = $testId.'_id';
            if($this->$column){
                $refOauth = $testId;
            }
        }
        return $refOauth;
    }

    public function adverts() {
        return $this->hasMany('App\Advert');
    }

    public function payments() {
        return $this->hasMany('App\Payment');
    }
}
