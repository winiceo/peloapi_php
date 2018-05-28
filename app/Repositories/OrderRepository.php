<?php

namespace App\Repositories;

use App\Models\Order;
use App\Scopes\StatusScope;
use App\User;

class OrderRepository
{
    use BaseRepository;

    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    /**
     * Get number of the records.
     *
     * @param  int $number
     * @param  string $sort
     * @param  string $sortColumn
     * @return Paginate
     */
    public function page($number = 10, $sort = 'asc', $sortColumn = 'created_at')
    {
        $this->model = $this->checkAuthScope();

        return $this->model->orderBy($sortColumn, $sort)->with('User')->paginate($number);
    }

    /**
     * Get the ad record without draft scope.
     * 
     * @param  int $id
     * @return mixed
     */
    public function getById($id)
    {
        $this->model = $this->checkAuthScope();

        return $this->model->with('User')->findOrFail($id);
    }

    /**
     * Store a new ad.
     * @param  array $data
     * @return Model
     */
    public function store($data)
    {
        $order = $this->model->create($data);

//        if (is_array($data['tags'])) {
//            $this->syncTag($order, $data['tags']);
//        } else {
//            $this->syncTag($order, json_decode($data['tags']));
//        }

        return $order;
    }

    /**
     * Update a record by id.
     * 
     * @param  int $id
     * @param  array $data
     * @return boolean
     */
    public function update(int $id, array $data)
    {
        $this->model = $this->checkAuthScope();

        $order = $this->model->findOrFail($id);

//        if (is_array($data['tags'])) {
//            $this->syncTag($order, $data['tags']);
//        } else {
//            $this->syncTag($order, json_decode($data['tags']));
//        }

        return $order->update($data);
    }

    /**
     * Update a record by id without tag.
     * 
     * @param  int $id
     * @param  array $data
     * @return boolean
     */
    public function updateWithoutTags(int $id, array $data)
    {
        $this->model = $this->checkAuthScope();

        $order = $this->model->findOrFail($id);

        return $order->update($data);
    }

    /**
     * Check the auth and the model without global scope when user is the admin.
     * 
     * @return Model
     */
    public function checkAuthScope()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $this->model = $this->model->withoutGlobalScope(StatusScope::class);
        }

        return $this->model;
    }

    /**
     * Sync the tags for the ad.
     *
     * @param  int $number
     * @return Paginate
     */
    public function syncTag(ad $order, array $tags)
    {
        return $order->tags()->sync($tags);
    }

    /**
     * Get a list of tag ids that are associated with the given ad.
     *
     * @param \App\ad $order
     *
     * @return array
     */
    public function listTagsIdsForad(ad $order)
    {
        return $order->tags->pluck('id')->toArray();
    }

    /**
     * Delete the draft article.
     *
     * @param int $id
     * @return boolean
     */
    public function destroy($id)
    {
        return $this->getById($id)->delete();
    }
}
