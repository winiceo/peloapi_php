<?php

namespace App\Controller\Api;




use App\Model\Order;
use App\Model\User;
use App\Model\WalletAddress;
use Illuminate\Pagination\Paginator;
use Leven\Facades\Cache;
use Leven\Facades\Lang;
use Leven\Facades\Log;
use Leven\Facades\Redis;

use DI\ContainerBuilder;
use Leven\Facades\Tran;
use Slim\Http\Request;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;


class TestController extends Controller
{


    public function test( )
    {
//        for($i=0;$i<1000;$i++){
//            $data['coin_type']=2;
//            $data['address']=uniqid();
//
//            WalletAddress::create($data);
//        }
        echo 444;
        //$cc=$this->container->get("databases");

        //dump($cc);
    }
   public function ok(Request $request )
    {
        $user=User::find(1);
        $a=$this->getHistory($request,$user);

        dump($a->toArray());
        $data=[];
       $data["data"]=$a->items();
        $data["page"]=[
            "current_page"=>$a->currentPage(),
            "total_page"=>$a->lastPage(),
            "total"=>$a->total(),

        ];

        dump($data);
//        "current_page": 1,
//            "total_page": 1,
//            "total": 1
//
//
//        'current_page' => $this->currentPage(),
//            'data' => $this->items->toArray(),
//            'first_page_url' => $this->url(1),
//            'from' => $this->firstItem(),
//            'next_page_url' => $this->nextPageUrl(),
//            'path' => $this->path,
//            'per_page' => $this->perPage(),
//            'prev_page_url' => $this->previousPageUrl(),
//            'to' => $this->lastItem(),

    }


    public  function getHistory(Request $request, User $user, int $perPage = 20, int $type = 0)
    {

        $order_by = $request->getParam('order_by', "id");
        $desc = $request->getParam('desc', 1);

        $data=Order::where(function ($query) use ($request, $user,$type) {

            $query->where('user_id', $user->id);
            $status = $request->getParam('status', -1);
            if ($status !== -1) {
                $query->where('status', $status);
            }
            if ($type > 0) {
                $query->where('type', $type);
            }

        })->orderBy($order_by, $desc == 1 ? "desc" : "asc")
            ->paginate($perPage);

        return $data;


    }



}
