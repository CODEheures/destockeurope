<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'topic_id',
        'token'
    ];

    //local scopes
    public function scopeExistIn($query, $token, $topic_id) {
        return $query->where('token', '=', $token)->where('topic_id', '=', $topic_id);
    }

    public function scopeExistInAll($query, $token) {
        return $query->where('token', '=', $token);
    }
}