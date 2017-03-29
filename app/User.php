<?php

namespace App;

use App\Common\LocaleUtils;
use App\Notifications\ResetPassword;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Notifications\Dispatcher;

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
        'requesterNumber',
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
    protected $appends = array('isDelegation');
    protected $casts = [
        'confirmed' => 'Boolean',
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

    public function getIsDelegationAttribute() {
        return $this->role=='delegation';
    }

    //local scopes
    public function scopeWhereMail($query, $email) {
        return $query->where('email', '=', $email);
    }

    public function scopeWhereRole($query, $role) {
        return $query->where('role', '=', $role);
    }

    //override
    /**
     * @override
     * @param $instance
     */
    public function notify($instance)
    {
        LocaleUtils::switchToUserLocale($this);
        app(Dispatcher::class)->send($this, $instance);
        LocaleUtils::switchToRuntimeLocale();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
