<?php
/**
 * Created by PhpStorm.
 * User: afshin
 * Date: 11/10/17
 * Time: 6:19 PM
 */

namespace App\Model;
use Core\Interfaces\_Model;

class User extends _Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * User tokens relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function addresss()
    {
        return $this->hasMany(Address::class)->orderBy('created_at', 'desc');
    }

    public function balances()
    {
        return $this->hasMany(UserBalance::class)->orderBy('created_at', 'asc');
    }
    /**
     * User Roles relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function roles()
    {
        return $this->belongsToMany('App\Model\Role');
    }


}