<?php

namespace App\Controller\Api;

use App\Helpers\CoinHelpers;
use App\Model\User;
use App\Repositories\AdvertRepository;
use App\Service\AdvertService;
use App\Validator\Advert;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Leven\Log;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class AdvertController extends Controller
{

    use Advert;


    public function overview(Request $request,Response $response)
    {
        $adverts = AdvertService::search($request,20);
        return $this->json($response,$adverts);
    }

    public function show(Request $request,Response $response,$id)
    {
        $advert = AdvertService::get($id);

        return $this->json($response,$advert);
    }


    public function getByUser(Request $request,Response $response){


        $adverts= AdvertService::getByUser($request,$this->user);
        $coins=CoinHelpers::getIds();
        foreach ($adverts as $k=>$v){
            $adverts[$k]->coin_name=$coins[$v->coin_type]['name'];
        }

        return  $this->json($response,$adverts);

    }


    public function store(Request $request,Response $response){


        if ($request->isPost()) {

            Log::info('request',[$request->getParams()]);
            $violations=$this->inputCheck($request);

            if (0 !== count($violations)) {
                $this->error($violations[0]->getMessage());
            }
            $data = $request->getParams();

            $data["user_id"] = $this->auth->getUser()->getUserId();


//            if ($this->auth->findByCredentials(['login' => $username])) {
//                $this->error('此用户名已存在');
//            }
//
//            if ($this->auth->findByCredentials(['login' => $email])) {
//                $this->error('此邮箱已存在');
//            }

            if ($this->validator->isValid()) {
                $advert=  AdvertService::store($data);
                return $this->json($response, $advert);

            }
        }

        return $this->fail($response);

    }






}
