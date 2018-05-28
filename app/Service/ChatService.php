<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/11/30
 * Time: 下午8:21
 */

namespace App\Service;


use App\Exception\ServerError;
use App\Model\Chat;
use App\Model\Order;
use App\Model\User;
use App\Model\UserBalance;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use JsonSchema\Validator;
use Leven\App;
use Leven\Auth;
use Leven\Lang;
use Leven\Log;
use Leven\Pusher;
use Monolog\Logger;
use Slim\Http\Request;
use Symfony\Component\Translation\Translator;
use DI\Annotation\Inject;


class ChatService
{

    public static function check($message)
    {

        $validator = new Validator();
        $validator->validate($message, (object)['$ref' => 'file://' . App::getRootDir() . '/src/Schema/message.json']);

        if (!$validator->isValid()) {

            foreach ($validator->getErrors() as $error) {
                Log::info('message error', ['message'=>sprintf("[%s] %s\n", $error['property'], $error['message'])]);
            }
            throw new ServerError('消息格式不正确');
            return false;
        }
        return true;
    }

    public static function getMessage(){
        $message=new \stdClass();
        $message->order_id=0;
        $message->from=0;
        $message->to=0;
        $message->public=0;
        $message->content=[
            'content'=>'',
            'type'=>'',
            'url'=>''
        ];
        $message->sendtime=Carbon::now()->timestamp;

        return $message;
    }

    public static function setMessage($order,$status){
        $content = \Leven\Request::getParam('content');
        if ($status) {
            $content = Lang::trans($status);
        }

        $user= Auth::getUser();
        $from_user_id=0;
        $to_user_id=0;
        if ($user->id == $order->ad_user_id) {
            $from_user_id=$order->ad_user_id;
            $to_user_id=$order->user_id;
        }

        if($user->id==$order->user_id){
            $from_user_id=$order->user_id;
            $to_user_id=$order->ad_user_id;
        }

        $message=self::getMessage();
        $message->from=$from_user_id;
        $message->to=$to_user_id;
        $message->order_id=$order->id;
        $message->public=1;
        $message->content  =  [
            "type" => 'Text',
            "content" => $content,
            "url"=>''
        ];
        self::store($message);
        return $message;
    }


    public static function setImageMessage($url){

        $order_id = \Leven\Request::getParam('orderId');
        $order=Order::find($order_id);
        $user= Auth::getUser();
        $from_user_id=0;
        $to_user_id=0;
        if ($user->id == $order->ad_user_id) {
            $from_user_id=$order->ad_user_id;
            $to_user_id=$order->user_id;
        }

        if($user->id==$order->user_id){
            $from_user_id=$order->user_id;
            $to_user_id=$order->ad_user_id;
        }

        $message=self::getMessage();
        $message->from=$from_user_id;
        $message->to=$to_user_id;
        $message->order_id=$order->id;
        $message->public=1;
        $message->content  =  [
            "type" => 'Image',
            "content" =>'' ,
            "url"=>$url
        ];
        self::store($message);
        return $message;
    }

    public static function store($message )
    {

        self::check($message);
        Pusher::trigger('private-chat-'.$message->order_id, 'Order', array('message' => $message));
        $data = [
            'order_id' => $message->order_id,
            'message' => \GuzzleHttp\json_encode($message),
            "from" => $message->from,
            "to" => $message->to,
        ];

        $ret = Chat::create($data);
        Log::info('消息添加成功', $data);
        return $ret;
    }


}