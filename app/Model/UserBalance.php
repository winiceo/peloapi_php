<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserBalance extends Model
{
    //

    protected $table='user_balances';

    protected $fillable = [
         'user_id', 'coin_type', 'coin_name', 'lock_balance', 'pending_balance', 'total_balance'
    ];
}
