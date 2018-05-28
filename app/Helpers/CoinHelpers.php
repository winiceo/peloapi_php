<?php

namespace App\Helpers;

use App\Model\CoinType;
use Illuminate\Contracts\Pagination\Paginator;

trait CoinHelpers
{

    public static function get()
    {
        $coins=CoinType::get();
        $temp=[];
        foreach ($coins as $coin){
            $temp[]= $coin->toArray();
        }
//        dump($temp);
//        $coin = [
//            [ "label" => '比特币', "value" => 1, "name" => "BTC"],
//            [ "label" => '以太币', "value" => 2, "name" => "ETH"],
//
//        ];

        return $temp;
    }

    public static function getIds()
    {
        $coins=CoinHelpers::get();
        $temp=[];
        foreach ($coins as $coin){

             $temp[$coin['id']]=$coin;
        }
        return $temp;
    }


}
