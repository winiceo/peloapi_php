<?php

namespace App\Model;

use Cartalyst\Sentinel\Users\EloquentUser;
use Symfony\Component\Validator\Constraints as Assert;

class User extends EloquentUser
{
    protected $table = 'user';

    protected $primaryKey = 'id';


    /**
     * @Assert\NotBlank()
     */
    public $username;

    protected $fillable = [
        'username',
        'mobile',
        'password',
        'last_name',
        'first_name',
        'permissions',
    ];

    protected $loginNames = ['username', 'mobile'];

    public function addresss()
    {
        return $this->hasMany(Address::class)->orderBy('created_at', 'desc');
    }

    public function balances()
    {
        return $this->hasMany(UserBalance::class)->orderBy('created_at', 'asc');
    }


}
