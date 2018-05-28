<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/11/30
 * Time: 下午8:21
 */

namespace App\Service;


use App\Model\Order;
use App\Model\OrderLog;
use App\Model\User;
use App\Model\UserBalance;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Leven\Log;
use Monolog\Logger;
use Slim\Http\Request;


class OrderService
{
    protected $order;

     public function __construct(Order $order)
    {
        $this->order=$order;


    }
    public static function get($id)
    {
        $advert=Order::find($id);
        return $advert;
    }

    public static function log($data){

         OrderLog::create($data);

         Log::info('order_status: ' , $data);

    }


    public function store($data){
        $ret =  Order::create($data);


        return $ret;
    }


    public function sellerSendMessage($content)
    {
        $from_user_id=$this->order->ad_user_id;
        $to_user_id=$this->order->user_id;


        $content = \GuzzleHttp\json_encode($content);

        $ret = app('rcloud')->Message()->publishPrivate(env('RONG_CLOUD_ID_PRE') . $from_user_id, env('RONG_CLOUD_ID_PRE') . $to_user_id, 'RC:TxtMsg', $content, '', '', '', 0, 1, 1, 1);
        return $ret;
    }

    public function buySendMessage($content){
        $from_user_id=$this->order->user_id;
        $to_user_id=$this->order->ad_user_id;


        $content = \GuzzleHttp\json_encode($content);

        $ret = app('rcloud')->Message()->publishPrivate(env('RONG_CLOUD_ID_PRE') . $from_user_id, env('RONG_CLOUD_ID_PRE') . $to_user_id, 'RC:TxtMsg', $content, '', '', '', 0, 1, 1, 1);
        return $ret;
    }
    //锁定币
    public   function sellerlockOrder( ){
        $uid=$this->order->ad_user_id;
        $coin_type=$this->order->coin_type;
        $block_balance=$this->order->qty;

        UserBalance::where('user_id',$uid)
            ->where('coin_type',$coin_type)
            ->increment('block_balance', $block_balance);


        Log::info('balance',['message'=>'锁定卖家币 increment balance: '.$block_balance]);

    }

    //卖家解锁资金；
    public   function sellerUnlockOrder( ){
        $uid=$this->order->ad_user_id;
        $coin_type=$this->order->coin_type;
        $block_balance=$this->order->qty;


        UserBalance::where('user_id',$uid)
            ->where('coin_type',$coin_type)
            ->decrement('block_balance', $block_balance);

        OrderService::log([
            "order_id"=>$this->order->id,
            "message"=>\GuzzleHttp\json_encode(["message"=>'seller unlock']),
            "status"=>0
        ]);

    }


    //放行订单
    public function orderRelease(){
        //卖单放行

        if($this->order->trade_type==0){
            $uid=$this->order->ad_user_id;
            $coin_type=$this->order->coin_type;
            $block_balance=$this->order->qty;

            //解锁
            UserBalance::where('user_id',$uid)
                ->where('coin_type',$coin_type)
                ->decrement('block_balance', $block_balance);
                //->decrement('total_balance', $block_balance);

            OrderService::log([
                "order_id"=>$this->order->id,
                "message"=>\GuzzleHttp\json_encode(["message"=>'seller unblock','block_balance'=>$block_balance]),
                "status"=>0
            ]);

            //减少总资产
            UserBalance::where('user_id',$uid)
                ->where('coin_type',$coin_type)
                ->decrement('total_balance', $block_balance);

            OrderService::log([
                "order_id"=>$this->order->id,
                "message"=>\GuzzleHttp\json_encode(["message"=>'seller decrement','block_balance'=>$block_balance]),
                "status"=>0
            ]);

            UserBalance::where('user_id',$this->order->user_id)
                ->where('coin_type',$coin_type)
                ->increment('total_balance', $block_balance);

            OrderService::log([
                "order_id"=>$this->order->id,
                "message"=>\GuzzleHttp\json_encode(["message"=>'buyer increment','balance'=>$block_balance]),
                "status"=>0
            ]);

        }


    }

    //获取广告发布者可用资产
    public function getAdUserBalance($ad){
       $balance= UserBalance::findById('user_id',$ad->ad_user_id);


    }

    public static function check_order(){
        $orders=Redis::command('hgetall', ['monitoring_order']);


        foreach ($orders as $k=>$v){

            $order=Order::find($k);
            $dt=new Carbon($v);
            $time=$dt->diffInMinutes(Carbon::now());                  // 0

            if($time>$order->payment_window_minutes&&$order->status==Config::get('constants.ORDER_STATUS.CREATED')){


                Redis::command("hdel",['monitoring_order',$k]);
                $orderService = new OrderService($order);

                $orderService->sellerUnlockOrder();

            }

        }
    }

    public static function getByUser(Request $request,User $user, int $perPage = 20): Paginator
    {

        $order_by = $request->getParam('order_by', "id");
        $desc = $request->getParam('desc', 1);

        return Order::with('aduser')->where(function ($query) use ($request,$user) {

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

}