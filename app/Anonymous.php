<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anonymous extends Model
{
    protected $fillable = [
        'name',
        'email',
        'compagnyName',
        'phone',
        'isNewsLetterSubscriber'
    ];

    //local scopes
    public function scopeWhereMail($query, $email) {
        return $query->where('email', '=', $email);
    }
}
