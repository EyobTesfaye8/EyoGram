<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $fillable = ['user_id', 'post_id'];
    protected function user(){
        return $this->belongsTo('user','user_id','id');
    }
    protected function post(){
        return $this->belongsTo('post', 'post_id', 'id');
    }
}
