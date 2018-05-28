<?php

namespace App\Controller\Api;




use App\Model\WalletAddress;
use Leven\Facades\Cache;
use Leven\Facades\Log;
use Leven\Facades\Redis;

use DI\ContainerBuilder;


class TestController extends Controller
{


    public function test( )
    {
        for($i=0;$i<1000;$i++){
            $data['coin_type']=2;
            $data['address']=uniqid();

            WalletAddress::create($data);
        }
        //$cc=$this->container->get("databases");

        //dump($cc);
    }



}
