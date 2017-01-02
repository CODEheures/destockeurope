<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use SoftDeletes;
    use NodeTrait;

    protected $fillable = ['description'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'description' => 'array'
    ];

    public function adverts() {
        return $this->hasMany('App\Advert');
    }

    //local scopes
    public function scopeDescendantsWith($query, $id) {
        return $query->with('descendants')->where('id', $id);
    }
}
