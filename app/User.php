<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use CascadeSoftDeletes;
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
    protected $cascadeDeletes = ['adverts', 'bookmarks'];
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

    public function bookmarks() {
        return $this->hasMany('App\Bookmark');
    }

    public function invoices() {
        return $this->hasMany('App\Invoice');
    }

    public function haveBookmark($id) {
        $result=false;
        foreach ($this->bookmarks as $bookmark){
            if($bookmark->advert_id == $id) {
                $result = true;
            }
        }
        return $result;
    }
}
