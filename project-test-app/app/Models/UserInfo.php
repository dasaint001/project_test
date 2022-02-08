<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';

    public function user() { 

        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}