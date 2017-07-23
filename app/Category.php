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
        'description' => 'array',
        'availableMoveTo' => 'array'
    ];
    protected $appends = ['canBeDeleted', 'availableMoveTo'];

    private $availableMoveTo = [];

    public function adverts() {
        return $this->hasMany('App\Advert');
    }

    public function getCanBeDeletedAttribute() {
        return $this->canBeDeleted;
    }

    public function getAvailableMoveToAttribute() {
        return $this->availableMoveTo;
    }

    public function setCanBeDeleted($value) {
        $this->canBeDeleted = $value;
    }

    public function setAvailableMoveTo() {
        $tree = Category::whereNotDescendantOf($this)->defaultOrder()->get();
        $tree = $tree->filter(function ($item, $key){
           return $item->id!=$this->id;
        });

        if(!$this->isRoot()){
            $descriptions = [];
            foreach (config('codeheuresUtils.availableLocales') as $lang){
                $descriptions[$lang] = trans('strings.form_dropdown_move_as_root',[],'',$lang);
            }
            $cat = new Category();
            $cat->description = $descriptions;
            $cat->id=-1;
            $tree->prepend($cat);
        }

        $tree = $tree->toTree();
        $this->availableMoveTo = array_values($tree->all());
    }

    //local scopes
    public function scopeDescendantsWith($query, $id) {
        return $query->with('descendants')->where('id', $id);
    }
}
