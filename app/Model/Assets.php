<?php

namespace App\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assets extends Model
{



    const TABLE = 'assets';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'coin_id'
    ];



}
