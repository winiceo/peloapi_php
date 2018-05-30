<?php

namespace App\Model;

use App\User;


class Order extends Model
{
    // Attributes.
    protected $table = 'order';

    protected $fillable = [
        'id', 'user_id', 'coin_id', 'address', 'amount', 'order_code','txid','type','balance', 'finish_time', 'status', 'created_at', 'updated_at'
    ];

}

