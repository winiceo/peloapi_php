<?php

namespace App\Model;
use App\User;


class OrderLog extends Model
{
    // Attributes.
     protected $table = 'order_log';
    protected $fillable = [
        'id', 'order_id', 'message','status','created_at', 'updated_at'
    ];
    protected $guarded = [];

    /* ---- Everything after this line will be preserved. ---- */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }



}
