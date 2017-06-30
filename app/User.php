<?php

namespace App;

use App\Common\LocaleUtils;
use App\Common\PrivilegesUtils;
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

    const ROLE_USER = 0;
    const ROLE_SUPPLIER = 1;
    const ROLE_ADMIN = 2;
    const ROLE_VALIDATOR = 3;
    const ROLE_ACCOUNTANT = 4;
    const ROLE_INTERMEDIARY = 5;
    const ROLES = ['user', 'supplier', 'admin', 'validator', 'accountant', 'intermediary'];

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
    protected $appends = array('isSupplier', 'rolesList', 'urlSetRole', 'urlDelete', 'isRemovable');
    protected $casts = [
        'confirmed' => 'Boolean',
    ];
    protected $hidden = [
        'password', 'remember_token', 'role', 'rolesList', 'urlSetRole'
    ];
    private $isRemovable = false;
    private $urlSetRole = '';
    private $urlDelete = '';



    //Relations
    public function adverts() { return $this->hasMany('App\Advert'); }
    public function bookmarks() { return $this->hasMany('App\Bookmark'); }
    public function invoices() { return $this->hasMany('App\Invoice'); }

    //Attributs Getters
    public function getIsSupplierAttribute() {
        return $this->role==static::ROLES[static::ROLE_SUPPLIER];
    }

    public function getRolesListAttribute() {
        return static::ROLES;
    }

    public function getUrlSetRoleAttribute() {
        return $this->urlSetRole;
    }

    public function getUrlDeleteAttribute() {
        return $this->urlDelete;
    }

    public function getIsRemovableAttribute() {
        return $this->isRemovable;
    }

    //public functions
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

    public function haveBookmark($id) {
        $result=false;
        foreach ($this->bookmarks as $bookmark){
            if($bookmark->advert_id == $id) {
                $result = true;
            }
        }
        return $result;
    }

    public function setIsRemovable() {
        $this->isRemovable = ($this->adverts()->withTrashed()->count() == 0) && (PrivilegesUtils::canDeleteUser($this));
    }

    public function setUrlSetRole() {
        $this->urlSetRole = route('admin.user.role.patch', ['id' => $this->id]);
    }

    public function setUrlDelete() {
        $this->urlDelete = route('admin.user.delete', ['id' => $this->id]);
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
