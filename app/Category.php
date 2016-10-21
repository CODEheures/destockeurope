<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'meta_category_id', 'parent_id', 'description'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'description' => 'array'
    ];

    public function metaCategory() {
        return $this->belongsTo('App\MetaCategory');
    }


    public function adverts() {
        return $this->hasMany('App\Advert');
    }
}
