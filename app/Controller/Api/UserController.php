<?php

namespace App\Controller\Api;

use App\Model\User;
use App\Model\UserBalance;
use App\Service\ParseService;
use App\Service\UserService;
use App\Service\UserWalletService;


use function App\username;
use App\Validation\UserRegister;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Firebase\JWT\JWT;
use Leven\Facades\Auth;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController extends Controller
{

    use UserRegister;
    public function jwtGenerator(User $user, $credentials)
    {
        $token = [
            "iss" => "http://pf.local",
            "iat" => time(),
            "nbf" => time(),
            'exp' => strtotime("+12 month"),
            "data" => [
                'id' => $user->id,
                'credentials'=>$credentials
            ],
        ];

        $jwt = JWT::encode($token, $this->container->get('secret-key'));

        return $jwt;
    }



    public function login(Request $request, Response $response)
    {

        if ($request->isPost()) {

            $login = (string) $request->getParam('login', '');
            $credentials = [
                 username($login) => $login,
                'password' => $request->getParam('password', ''),
            ];


            $remember =  true;

            try {
                if ($this->auth->authenticate($credentials, $remember)) {
                    //$this->flash('success', 'You are now logged in.');
                    $data=[
                        "user"=>$this->auth->getUser(),
                        "token"=>$this->jwtGenerator($this->auth->getUser(),$credentials)
                    ];

                    return $this->json($response,$data);


                    //return $this->redirect($response, 'home');
                } else {
                    $this->error( '用户名或密码错误');
                }
            } catch (ThrottlingException $e) {
                $this->error('频率受限');
            }
        }
        return $this->fail($response);


    }

    public function register(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $username = $request->getParam('username');
            $mobile = $request->getParam('mobile');
            $password = $request->getParam('password');


            $violations=$this->inputCheck($request);

            if (0 !== count($violations)) {
                $this->error($violations[0]->getMessage());
            }


            if ($this->auth->findByCredentials(['login' => $username])) {
                $this->error('此用户名已存在');
            }

            if ($this->auth->findByCredentials(['login' => $mobile])) {
                $this->error('此手机已注册');
            }

            if ($this->validator->isValid()) {
                $role = $this->auth->findRoleByName('User');

                $user = $this->auth->registerAndActivate([
                    'username' => $username,
                    'mobile' => $mobile,
                    'password' => $password,
                    'permissions' => [
                        'user.delete' => 0
                    ]
                ]);
                //UserService::checkWallet($user);


                //$role->users()->attach($user);

//                $parse=new ParseService();
//                $parse->register($user);
                //$this->flash('success', 'Your account has been created.');

                $data=[
                    "user"=>$user,
                    "token"=>$this->jwtGenerator($user,$this->container->get('secret-key'))
                ];

                return $this->json($response,$data);


            }
        }

        return $this->fail($response);
    }



    /**
     * 忘记密码
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function forget(Request $request, Response $response)
    {
        if ($request->isPost()) {


            //$username = $request->getParam('username');
            $mobile = $request->getParam('mobile');
            $password = $request->getParam('password');

            $violations=$this->forgetInputCheck($request);

            if (0 !== count($violations)) {
                $this->error($violations[0]->getMessage());
            }

            if (!$this->auth->findByCredentials(['login' => $mobile])) {
                $this->error('此手机号没被注册');
            }

            if ($this->validator->isValid()) {
                //$role = $this->auth->findRoleByName('User');
                $credentials = [
                    'mobile' => $mobile,
                ];

                $user = $this->auth->findByCredentials($credentials);

                 $meta = [
                    'password' => $password,
                ];

                $user = $this->auth->update($user, $meta);

                return $this->json($response,$user);


            }
        }

        return $this->fail($response);
    }



    /**
     * 修改密码
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function change_password(Request $request, Response $response)
    {
        if ($request->isPost()) {

            $user=$this->auth->getUser();

            $old_password = $request->getParam('old_password');
            $password = $request->getParam('password');

            $credentials = [

                'password' => $old_password,
            ];
            if(!$this->auth->validateCredentials($user,$credentials)){
                $this->error('旧密码不正确');
            }


            $violations=$this->UpdatePasswordInputCheck($request);

            if (0 !== count($violations)) {
                $this->error($violations[0]->getMessage());
            }

            if ($this->validator->isValid()) {

                $meta = [
                    'password' => $password,
                ];

                $user = $this->auth->update($user, $meta);

                return $this->json($response,$user);


            }
        }

        return $this->fail($response);
    }



    public function profile(Request $request, Response $response){
        $user=$this->auth->getUser();
        return $this->json($response,$user);
    }

    public function getBalances(Request $request, Response $response){
        $user=$this->auth->getUser();
        UserWalletService::checkWallet($user);
        $balance = UserBalance::
            where('user_id', $user->id)
            ->get();
        return $this->json($response,$balance);
    }





}
