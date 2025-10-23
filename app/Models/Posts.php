<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posts extends Model
{
    protected $fillable = ['user_id', 'content', 'image_url'];

    protected function user(){
        return $this->belongsTo('user', 'user_id', 'id');
    }

    protected function comment() {
        return $this->hasMany('comments', 'post_id', 'id');
    }
}
