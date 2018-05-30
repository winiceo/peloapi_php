<?php

namespace App\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoinType extends Model
{


    const TABLE = 'coin_type';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    protected $vote = User::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'symbol','decimals','withdraw_enable','fee'
    ];

}
