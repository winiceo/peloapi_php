<?php
//
//use DI\Annotation\Inject;
//use Slim\Views\Twig;
//
$this->map(['GET', 'POST'], '/api/cc', ['api.test', 'test']);

//
//
////$app->options('/{routes:.+}', function ($request, $response, $args) {
////    return $response;
////});
//
//$app->add(function ($req, $res, $next) {
//    $response = $next($req, $res);
//    return $response
//        ->withHeader('Access-Control-Allow-Origin', '*')
//        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//});
//
//$app->get('/bootstrappers', function ($request, $response) {
//    $data = [
//        'app_name' => 'beeotc',
//    ];
//    return $response->withJson($data);
//
//});
//
//
//$app->group('/api/v2', function () use ($container) {
//    $this->group('/test', function () use ($container) {
//        $this->map(['GET', 'POST'], '/ok', ['api.test', 'ok'])->add($container->get('jwt.middleware'));;;
//    });//->add($container->get('jwt.middleware')) ;;
//    $this->get('/bootstrappers', function ($request, $response) {
//        $data = [
//            'app_name' => 'beeotc',
//            'coin_type' => \App\Service\BaseService::getCoinType(),
//
//            'payment_provider'=>[
//                ['1'=>'支付宝'],
//                ['2'=>'微信'],
//                ['4'=>'银联转账']
//
//            ]
//        ];
//        return $response->withJson(['status' => 200, 'data' => $data]);
//    });
//
//
//    $this->map(['GET', 'POST'],'/ticket', ['api.common', 'getPrice']);
//
//    $this->post('/upload', ['api.upload', 'upload'])->add($container->get('jwt.middleware'));;
//    $this->get('/upload', ['api.upload', 'index']);
//
//
//    $this->post('/user/login', ['api.user', 'login']);
//    $this->post('/user/register', ['api.user', 'register']);
//    $this->group('/user', function () {
//        $this->post('/profile', ['api.user', 'profile']);
//        $this->post('/logout', ['api.user', 'logout']);
//        $this->post('/safe/check', ['api.user', 'check']);
//        $this->post('/advert', ['api.advert', 'getByUser']);
//        $this->post('/order', ['api.order', 'getByUser']);
//
//        $this->post('/balance', ['api.user', 'getBalances']);
//
//
//    })->add($container->get('jwt.middleware'));
//
//    $this->group('/finance', function () {
//        $this->post('/depoist', ['api.user', 'depoist']);
//        $this->post('/withdraw', ['api.user', 'withdraw']);
//    })->add($container->get('jwt.middleware'));
//
//
//    $this->group('/wallet', function () {
//        $this->post('/address', ['api.wallet', 'address']);
//        $this->post('/address/store', ['api.wallet', 'storeAddress']);
//        $this->post('/deposit/{id}', ['api.wallet', 'deposit']);
//        $this->post('/withdraw', ['api.wallet', 'withdraw']);
//        $this->post('/withdraw/history', ['api.wallet', 'history']);
//
//    })->add($container->get('jwt.middleware'));
//
//
//    $this->group('/advert', function ()use($container) {
//        $this->post('', ['api.advert', 'overview']);
//        $this->post('/store', ['api.advert', 'store'])->add($container->get('jwt.middleware'));
//        $this->post('/detail/{id}', ['api.advert', 'show']);
//    });
//
//
//    $this->group('/verifycodes', function () {
//        $this->post('', ['api.common', 'captcha']);
//        $this->post('/register', ['api.common', 'captchaReg']);
//    });
//
//    $this->group('/order', function () {
//        $this->post('/detail/{id}', ['api.order', 'show']);
//        $this->post('/store', ['api.order', 'store']);
//        $this->post('/pay', ['api.order', 'pay']);
//        $this->post('/cancel', ['api.order', 'cancel']);
//        $this->post('/release', ['api.order', 'release']);
//        $this->post('/comment', ['api.order', 'comment']);
//        $this->post('/complaint', ['api.order', 'complaint']);
//
//    })->add($container->get('jwt.middleware'));
//
//    $this->group('/im', function () {
//        $this->post('/message/send', ['api.im', 'send']);
//        $this->post('/message/history', ['api.im', 'history']);
//        $this->map(['POST','GET'],'/auth', ['api.im', 'auth']);
//    })->add($container->get('jwt.middleware'));
//
//});
//
//
//
//
//
//
//
//
//
