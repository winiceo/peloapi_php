<?php

namespace App\Controller\Api;

use App\Model\User;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;
use \Yunpian\Sdk\YunpianClient;
use App\Leven;
class CommonController extends Controller
{



    public function captcha(Request $request, Response $response)
    {

        $mobile = $request->getParam('mobile');



        $code=Leven::generate_code(4);
       // Redis::command('hset', ['safe_check', $mobile,$code]);


        if($mobile){
            //初始化client,apikey作为所有请求的默认值
            $clnt = YunpianClient::create(env("YUNPIAN_API"));
            $param = [YunpianClient::MOBILE => $mobile,YunpianClient::TEXT => '【币赢科技】 您的验证码是 '.$code];
            $r = $clnt->sms()->single_send($param);
            return $this->setMessage('发送成功')->json($response,[]);
        }
        return $this->setMessage('发送成功')->json($response,[]);
    }


    public function captchaReg(Request $request, Response $response){

        $mobile = $request->getParam('mobile');

        $code=Leven::generate_code(4);
        // Redis::command('hset', ['safe_check', $mobile,$code]);


        if($mobile){
            //初始化client,apikey作为所有请求的默认值
            $clnt = YunpianClient::create(env("YUNPIAN_API"));
            $param = [YunpianClient::MOBILE => $mobile,YunpianClient::TEXT => '【币赢科技】 您的验证码是 '.$code];
            $r = $clnt->sms()->single_send($param);
            return $this->setMessage('发送成功')->json($response,[]);
        }
        return $this->setMessage('发送成功')->json($response,[]);
    }

    public function getprice(Request $request, Response $response){

        $price=[
            "btc_cny"=>12345678.22,
            "eth_cny"=>4022.33,

        ];
        return $this->setMessage('发送成功')->json($response,$price);
    }
}
