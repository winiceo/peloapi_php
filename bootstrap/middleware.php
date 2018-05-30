<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

use Psr\Container\ContainerInterface;


$container->set('guest.middleware',function (ContainerInterface $container) {
    //return new GuestMiddleware($container->get('router'), $container->get('auth'));
});

$container->set('auth.middleware',function ( ContainerInterface $container) {
    return function ($role = null) use ($container) {
        //return new AuthMiddleware($container->get('router'), $container->get('flash'), $container->get('auth'), $role);
    };
});

$container->set('jwt.middleware',  function (ContainerInterface $container) {

    return new Tuupola\Middleware\JwtAuthentication([
        "secret" => $container->get('secret-key'),
        "secure" => false,
        "path" => "/api/v2",
        "algorithm" => ["HS256"],
        "passthrough" => ["/api/v2/user/login"],

        "attribute" => "jwt",
        'message'=>'',
        "callback" => function ($request, $response, $arguments)use($container)   {

            dump($arguments["decoded"]);
            $container->get('logger')->info('Requst:',[$request->getHeaders()]);


            dump($arguments["decoded"]);
            $container->set('jwt',$arguments["decoded"]);
            $user= $container->get('auth')->authenticate((array)$arguments["decoded"]->data->credentials,true);
            if(!$user){
                return false;
            }

            $container->get('monolog')->info('AuthUser:',[$user]);



        },
        "error" => function (\Psr\Http\Message\ResponseInterface $response, array $arguments) {
            $data["status"] = "401";
           // $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]);

});
//$app->add($container['csrf']);
return $container;

