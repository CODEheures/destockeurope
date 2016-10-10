<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'meta_category_id', 'title'];
    protected $dates = ['deleted_at'];

    public function metaCategory() {
        return $this->belongsTo('App\MetaCategory');
    }
}
