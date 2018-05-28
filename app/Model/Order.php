<?php

namespace App\Model;

use App\User;


/**
 * @property integer $id [TYPE=INTEGER, NULLABLE=0, DEFAULT=""]
 * @property string $order_code [TYPE=STRING, NULLABLE=0, DEFAULT=""]
 * @property integer $ad_id [TYPE=INTEGER, NULLABLE=0, DEFAULT=""]
 * @property string $ad_code [TYPE=STRING, NULLABLE=0, DEFAULT=""]
 * @property integer $ad_user_id [TYPE=INTEGER, NULLABLE=0, DEFAULT=""]
 * @property integer $user_id [TYPE=INTEGER, NULLABLE=0, DEFAULT=""]
 * @property decimal $ad_price [TYPE=DECIMAL, NULLABLE=0, DEFAULT="0.00"]
 * @property decimal $amount [TYPE=DECIMAL, NULLABLE=0, DEFAULT="0.00"]
 * @property decimal $qty [TYPE=DECIMAL, NULLABLE=0, DEFAULT="0.00"]
 * @property integer $payterm [TYPE=INTEGER, NULLABLE=0, DEFAULT="30"]
 * @property string $finish_time [TYPE=DATETIME, NULLABLE=1, DEFAULT="NULL"]
 * @property boolean $status [TYPE=BOOLEAN, NULLABLE=1, DEFAULT="0"]
 * @property string $order_desc [TYPE=TEXT, NULLABLE=0, DEFAULT=""]
 * @property string $buyer_estimate [TYPE=TEXT, NULLABLE=0, DEFAULT=""]
 * @property string $seller_estimate [TYPE=TEXT, NULLABLE=0, DEFAULT=""]
 * @property string $created_at [TYPE=DATETIME, NULLABLE=1, DEFAULT="NULL"]
 * @property string $updated_at [TYPE=DATETIME, NULLABLE=1, DEFAULT="NULL"]
 */
class Order extends Model
{
    // Attributes.
    protected $table = 'orders';
    protected $fillable = [
        'id', 'order_code', 'ad_id', 'ad_code', 'ad_user_id', 'user_id', 'ad_price', 'amount', 'qty', 'payterm',
        'finish_time', 'status', 'order_desc', 'buyer_estimate', 'seller_estimate', 'created_at', 'updated_at', 'coin_type'
    ];
    protected $guarded = [];

    /* ---- Everything after this line will be preserved. ---- */

    public function user()
    {
        return $this->belongsTo(\App\Model\User ::class);
    }

    public function aduser(){
        return $this->hasOne('App\Model\User','id','ad_user_id');

    }

    public function comments()
    {

        return $this->hasMany(OrderComment::class)->orderBy('created_at', 'desc');

        //return $this->hasOne('App\User', 'id', 'ad_user_id');

    }

}
