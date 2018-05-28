<?php

namespace App\Controller\Api;



use App\Leven;
use App\Model\Chat;
use App\Model\Order;
use App\Service\ChatService;
use Carbon\Carbon;
use Leven\Auth;
use Leven\Log;
use Pusher\Pusher;
use Slim\Http\Request;
use Slim\Http\Response;

class ImController extends Controller
{

    public function send(Request $request)
    {
        $params = $request->getParams();
        $message=ChatService::getMessage();
        $message=(object) array_merge((array) $message,(array) $params );

        $ret=ChatService::store($message);

        return $this->setMessage('')->setData($message)->success();

    }

    public function history(Request $request, Chat $chat){

//        $messages=Chat::where(function ($query) use ($request) {
////            $order_id = $request->getParam('order_id', 0);
////
////            Log::info('id',[$order_id]);
////            $query->where('order_id', $order_id);
//        });//->orderBy('id', 'desc');

        $messages=Chat::where('order_id', $request->getParam('order_id', 0))
            ->orderBy('id','asc')->get();

        Log::info('order',[Auth::getUser()]);

        $temp=[];

        foreach($messages as $message){
            $temp[]=\GuzzleHttp\json_decode($message['message']);
        }

        return $this->setMessage('')->setData($temp)->success();

    }

    public function auth(Request $request,Response $response){

        $user=Auth::getUser();
        Log::info('user',[$user->toArray()]);

        $order_id=str_replace('private-chat-','',$request->getParam('channel_name'));
        $order=Order::find($order_id);
        if($order&&in_array($user->id,[$order->ad_user_id,$order->user_id])){
            $auth = \Leven\Pusher::socket_auth($request->getParam('channel_name'), $request->getParam('socket_id') );

            header('Content-Type: application/javascript');
            echo(    $auth );
        }else{

            return $response->withJson(['status'=>403,'message'=>'Access denied, the user does not have access to this section'], 403);

            //return $this(403)->getBody()->write();

        }






    }

    public function upload(){



    }

}
