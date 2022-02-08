<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    public function owner() { 

        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}