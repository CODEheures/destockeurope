<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    private $canBeDeleted = false;

    use SoftDeletes;
    use NodeTrait;

    protected $fillable = ['description'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'description' => 'array'
    ];
    protected $appends = ['canBeDeleted'];

    public function adverts() {
        return $this->hasMany('App\Advert');
    }

    public function getCanBeDeletedAttribute() {
        return $this->canBeDeleted;
    }

    public function setCanBeDeleted($value) {
        $this->canBeDeleted = $value;
    }

    //local scopes
    public function scopeDescendantsWith($query, $id) {
        return $query->with('descendants')->where('id', $id);
    }
}
