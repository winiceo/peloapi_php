<?php

namespace App\Controller\Api;

use App\Model\User;
use App\Service\UserWalletService;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Interop\Container\ContainerInterface;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class WalletController extends Controller
{
    public function __construct(ContainerInterface $container)
    {
       parent::__construct($container);
    }



    public function address(Request $request, Response $response){
//        $user=$this->auth->getUser();
//        if(!$user){
//            $this->error('获取用户失败');
//            return $this->fail($response,[]);
//        }


        $address=UserWalletService::address($this->auth->getUser());
        return $this->json($response,$address);
    }


    public function storeAddress(Request $request, Response $response){
        if ($request->isPost()) {
            $this->validator->request($request, [
                'coin_type' =>v::numeric()->length(1,1),
                'address' => v::stringType()->notEmpty()

            ]);
            $data = $request->getParams();

            $data["user_id"] = $this->auth->getUser()->getUserId();

            if (UserWalletService::isExistAddress($data)!=null) {
                $this->error('已存在此地址');
            }
            if ($this->validator->isValid()) {


                $address = UserWalletService::storeAddress($data);
                return $this->json($response, $address);
            }
            return $this->fail($response);
        }
        return $this->fail($response);

    }

    public function deposit(Request $request, Response $response,$args){

        $coin_type=$args['id'];
        $user_id = $this->auth->getUser()->getUserId();


        $address=UserWalletService::getWalletAddress($user_id,$coin_type);
        return $this->json($response,$address);

    }


    public function withdraw(Request $request, Response $response){


        if ($request->isPost()) {
            $this->validator->request($request, [
                'coin_type' =>v::numeric()->length(1,1),
                'address' => v::stringType()->notEmpty(),
                'amount' => v::numeric()->length(1,20)->notEmpty()

            ]);
            $data = $request->getParams();

            $data["user_id"] = $this->auth->getUser()->getUserId();

            $data['order_code']=time();

            if ($this->validator->isValid()) {


                $address = UserWalletService::storeWithdraw($data);
                return $this->json($response, $address);
            }else{
                return $this->error("input error");
            }

        }
        return $this->error($response);

    }


    public function history(Request $request, Response $response){


     $withdraws=UserWalletService::getHistory($request,$this->auth->getUser());
    return $this->json($response,$withdraws);

    }



}
