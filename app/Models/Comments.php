<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $fillable = ['content', 'user_id', 'post_id'];

    protected function user(){
        return $this->belongsTo('user', 'user_id', 'id');
    }

    protected function post(){
        return $this->belongsTo('posts', 'post_id', 'id');
    }
}
