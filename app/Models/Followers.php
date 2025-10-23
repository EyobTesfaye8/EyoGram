<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
    protected $fillable = ['folloer_id', 'following_id'];
    
    protected function followerUser() {
        return $this->belongsTo('Users', 'follower_id','id');
    }
    protected function followingUser() {
        return $this->belongsTo('Users', 'following_id','id');
    }
}
