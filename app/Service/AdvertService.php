<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/12/10
 * Time: 下午11:08
 */

namespace App\Service;


use App\Model\Advert;
use App\Model\User;
use App\Repositories\AdvertRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Slim\Http\Request;

class AdvertService
{


    public static function search(Request $request, int $perPage = 20): Paginator
    {
        $order_by = $request->getParam('order_by', "price");
        $desc = $request->getParam('desc', 1);

        return Advert::with("user")
            ->where(function ($query) use ($request) {
                $trade_type = $request->getParam('trade_type', -1);
                if($trade_type!==-1) {
                    $query->where('trade_type', $trade_type);
                }
                $coin_type = $request->getParam('coin_type', -1);
                if($coin_type!==-1){
                    $query->where('coin_type', $coin_type);
                }
            })->orderBy($order_by, $desc==1?"desc":"asc")

            ->paginate($perPage);
    }

    public static function getByUser(Request $request,User $user, int $perPage = 20): Paginator
    {

        $order_by = $request->getParam('order_by', "price");
        $desc = $request->getParam('desc', 1);

        return Advert::
             where(function ($query) use ($request,$user) {

                $query->where('user_id', $user->id);
                $trade_type = $request->getParam('trade_type', -1);
                if($trade_type!==-1) {
                    $query->where('trade_type', $trade_type);
                }
                $coin_type = $request->getParam('coin_type', -1);
                if($coin_type!==-1){
                    $query->where('coin_type', $coin_type);
                }
            })->orderBy($order_by, $desc==1?"desc":"asc")

            ->paginate($perPage);
    }


    public static function get($id)
    {
        $advert=Advert::find($id);


        return $advert;
    }


    public static function store($data){

        $ret = Advert::create($data);


        return $ret;
    }
}