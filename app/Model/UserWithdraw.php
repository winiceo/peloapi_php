<?php

namespace App\Model;

use models;

/**
 * @property integer $id [TYPE=INTEGER, NULLABLE=0, DEFAULT=""]
 * @property string $coin_type [TYPE=STRING, NULLABLE=0, DEFAULT=""]
 * @property string $address [TYPE=STRING, NULLABLE=0, DEFAULT=""]
 * @property decimal $amount [TYPE=DECIMAL, NULLABLE=0, DEFAULT=""]
 * @property string $order_code [TYPE=STRING, NULLABLE=0, DEFAULT=""]
 * @property string $finish_time [TYPE=DATETIME, NULLABLE=1, DEFAULT="NULL"]
 * @property boolean $status [TYPE=BOOLEAN, NULLABLE=1, DEFAULT="0"]
 * @property string $created_at [TYPE=DATETIME, NULLABLE=1, DEFAULT="NULL"]
 * @property string $updated_at [TYPE=DATETIME, NULLABLE=1, DEFAULT="NULL"]
 */
class UserWithdraw extends Model
{
    // Attributes.
     protected $table = 'user_withdraw';
    protected $fillable = [
        'id', 'user_id', 'coin_type', 'address', 'amount', 'order_code', 'finish_time', 'status', 'created_at', 'updated_at'
    ];
    protected $guarded = [];

    /* ---- Everything after this line will be preserved. ---- */
}
