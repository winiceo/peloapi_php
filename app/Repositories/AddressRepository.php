<?php

namespace App\Repositories;

 use App\Models\Address;
 use App\Tools\Mention;
use App\Notifications\GotVote;
use App\Notifications\MentionedUser;

class AddressRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Address $address)
    {
        $this->model = $address;
    }

    /**
     * Store a new record.
     *
     * @param  $input
     * @return User
     */
    public function store($input)
    {

        $address = $this->save($this->model, $input);


        return $address;
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

    public function getByaddress($address)
    {
        return $this->model->where('wallet_address', $address)

                    ->first();
    }

    /**
     * Toogle up vote and down vote by user.
     * 
     * @param  int $id
     * @param  boolean $isUpVote
     * 
     * @return boolean
     */
    public function toggleVote($id, $isUpVote = true)
    {
        $user = auth()->user();

        $address = $this->getById($id);

        if($address == null) {
            return false;
        }

        return $isUpVote
                ? $this->upOrDownVote($user, $address)
                : $this->upOrDownVote($user, $address, 'down');
    }

    /**
     * Up vote or down vote item.
     * 
     * @param  \App\User $user
     * @param  \Illuminate\Database\Eloquent\Model $target
     * @param  string $type
     * 
     * @return boolean
     */
    public function upOrDownVote($user, $target, $type = 'up')
    {
        $hasVoted = $user->{'has' . ucfirst($type) . 'Voted'}($target);

        if($hasVoted) {
            $user->cancelVote($target);
            return false;
        }

        if ($type == 'up') {
            $target->user->notify(new GotVote($type . '_vote', $user, $target));
        }

        $user->{$type . 'Vote'}($target);

        return true;
    }
}
