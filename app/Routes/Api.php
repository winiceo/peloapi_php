<?php

use DI\Annotation\Inject;
use Slim\Views\Twig;


$this->map(['GET', 'POST'], '/api/test/ok', ['api.test', 'ok']);


//$app->options('/{routes:.+}', function ($request, $response, $args) {
//    return $response;
//});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->get('/bootstrappers', function ($request, $response) {
    $data = [
        'app_name' => 'beeotc',
    ];
    return $response->withJson($data);

});


$app->group('/api', function () use ($container, $app) {
    $this->group('/test', function () use ($container, $app) {
        $this->map(['GET', 'POST'], '/okc', ['api.test', 'ok'])->add($container->get('jwt.middleware'));;;
    });//->add($container->get('jwt.middleware')) ;;


//
//    $this->map(['GET', 'POST'],'/ticket', ['api.common', 'getPrice']);
//
//    $this->post('/upload', ['api.upload', 'upload'])->add($container->get('jwt.middleware'));;
//    $this->get('/upload', ['api.upload', 'index']);
//
//
    $this->post('/account/login', ['api.user', 'login']);
    $this->post('/account/register', ['api.user', 'register']);
    $this->post('/account/forget', ['api.user', 'forget']);
    $this->post('/account/changePassword', ['api.user', 'change_password']);

    $this->group('/account', function () {
        $this->post('/profile', ['api.user', 'profile']);
        $this->post('/balance', ['api.user', 'getBalances']);

    })->add($container->get('jwt.middleware'));


    $this->group('/wallet', function () {
        $this->post('/assets', ['api.assets', 'index']);
        $this->post('/withdraw', ['api.assets', 'withdraw']);
        $this->post('/withdraw/history', ['api.assets', 'history']);

    })->add($container->get('jwt.middleware'));


    $this->group('/mobile/captcha', function () {
        $this->post('', ['api.common', 'captcha']);
        $this->post('/register', ['api.common', 'captchaReg']);
    });


    $this->group('/coin', function () {

        $this->post('/price', ['api.coin', 'price']);
    });

    $this->group('/balance', function () {
        $this->post('', ['api.balance', 'index']);
        $this->post('/income', ['api.balance', 'income']);
        $this->post('/withdraw', ['api.balance', 'withdraw']);
    });


    $this->group('/timeline', function () use ($container) {
        $this->post('', ['api.advert', 'overview']);
        $this->post('/store', ['api.advert', 'store'])->add($container->get('jwt.middleware'));
        $this->post('/detail/{id}', ['api.advert', 'show']);
    });


});