<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetaCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['description'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'description' => 'array'
    ];

    public function categories() {
        return $this->hasMany('App\Category');
    }

    public function parentCategories() {
        return $this->categories()->where('parent_id', $this->id);
    }

}
