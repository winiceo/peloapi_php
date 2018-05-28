<?php

namespace App\Repositories;

 use App\Models\Address;
 use App\Models\UserWithdraw;
 use App\Tools\Mention;
use App\Notifications\GotVote;
use App\Notifications\MentionedUser;

class WithdrawRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(UserWithdraw $withdraw)
    {
        $this->model = $withdraw;
    }

    /**
     * Store a new record.
     *
     * @param  $input
     * @return User
     */
    public function store($input)
    {

        $withdraw = $this->save($this->model, $input);


        return $withdraw;
    }

    /**
     * Save the input's data.
     *
     * @param  $model
     * @param  $input
     * @return User
     */
    public function save($model, $input)
    {
        $model->fill($input);

        $model->save();

        return $model;
    }


}
