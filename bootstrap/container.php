<?php


use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Native\SentinelBootstrapper;
use Illuminate\Database\Capsule\Manager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

use Psr\Container\ContainerInterface;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\MessageSelector;
use Monolog\Formatter\JsonFormatter;

$config = C();



$capsule = new Manager();
$capsule->addConnection($config['settings']['databases']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();


$container['secret-key'] = $config['settings']['secret-key'];
$container["jwt"] = function ($container) {
    return new StdClass;
};
$container["config"] = function () {
    $config = [];
    $config['constants'] = ['ORDER_STATUS' => [
        "CREATED" => 0,
        "PAY" => 1,
        "RELEASE" => 2,
        "COMMENT" => 3,
        "COMPLAINT" => 4,
        "CANCEL" => 8,
        "FINISH" => 9,

    ]];
   // return new Illuminate\Config\Repository($config);
};

$container['auth'] = function (ContainerInterface $c) {
    $sentinel = new Sentinel(new SentinelBootstrapper($c->get('settings')['sentinel']));
    return $sentinel->getSentinel();
};

$container['flash'] = function () {
    return new Messages();
};

$container['csrf'] = function (ContainerInterface $c) {
    $guard = new Guard();
    $guard->setFailureCallable($c->get('csrfFailureHandler'));

    return $guard;
};

// https://github.com/awurth/SlimValidation
$container['validator'] = function () {
    return new Validator();
};

$container['translator'] = function (ContainerInterface $container) {


    $translator = new Translator("zh_CN", new MessageSelector());
// Set a fallback language incase you don't have a translation in the default language
    $translator->setFallbackLocales(['zh_CN']);
// Add a loader that will get the php files we are going to store our translations in
    $translator->addLoader('php', new PhpFileLoader());
// Add language files here
    $translator->addResource('php', C('root') . '/translations/zh_cn/message.php', 'zh_CN'); // Norwegian

    //$translator->addResource('php', './translations/en_US.php', 'en_US'); // English
    return $translator;

};

$container['logger'] = function (ContainerInterface $c) {
    $config = $c->get('settings')['monolog'];

    $logger = new Logger($config['name']);
    $file_stream = new  StreamHandler($config['path'] . date("Y-m-d-") . getenv('APP_ENV') . '.log', Logger::INFO);
    $file_stream->setFormatter(new  JsonFormatter());

    $logger->pushHandler($file_stream);

    $logger->pushProcessor(new UidProcessor());

    return $logger;
};

//$container['parse']=function($container){
//    $config = $container->get('settings')['parse'];
//
//    \Parse\ParseClient::initialize( $config['appid'], null, $config['marst_key'] );
//
//    \Parse\ParseClient::setServerURL($config['server_url'],$config['mount']);
//
//
//};
//$container['pusher']=function($container){
//
//
//    $pusher = new Pusher\Pusher(getenv('PUSHER_APP_KEY'), getenv('PUSHER_APP_SECRET'), getenv("PUSHER_APP_ID"), array('cluster' => getenv('PUSHER_APP_CLUSTER')));
//    return $pusher;
//
//};

$container['cache'] = function (ContainerInterface $c) {
    $config = C()['settings']['redis'];



    $redis = [
        'schema' => $config['schema'],
        'host' => $config['host'],
        'port' => $config['port'],
        // other options
    ];
    $connection = new Predis\Client($redis);
    return new Symfony\Component\Cache\Adapter\RedisAdapter($connection);
};

$container['redis'] = function (ContainerInterface $c) {
    $config = C()['settings']['redis'];


    $redis = [
        'schema' => $config['schema'],
        'host' => $config['host'],
        'port' => $config['port'],
        // other options
    ];

    $client = new Predis\Client($redis);
    return $client;
};


$container['twig'] = function (ContainerInterface $c) {

    $config = $c->get('settings')['twig'];
    $twig = new Twig($config['path'], $config['options']);

    $twig->addExtension(new TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $twig->addExtension(new DebugExtension());
    $twig->addExtension(new CsrfExtension($c->get('csrf')));
    $twig->addExtension(new ValidatorExtension($c->get('validator')));
    $twig->addExtension(new AssetExtension($c->get('request')));

    $twig->getEnvironment()->addGlobal('flash', $c->get('flash'));
    $twig->getEnvironment()->addGlobal('auth', $c->get('auth'));

    return $twig;
};





/*Dynamic containers in services*/
$dir = scandir(__APP_ROOT__.'/leven/Services/');
$ex_folders = array('..', '.');
$filesInServices =  array_diff($dir,$ex_folders);

foreach($filesInServices as $service){
    $content = preg_replace('/.php/','',$service);
    $container[$content] = function () use ($content){
        $class =  '\\Core\\Services\\'.$content ;
        return new $class();
    };
}


// data access container
$array = getDirFiles(__APP_ROOT__.'/app/DataAccess/');
foreach($array as $key=>$item){
    $classDataAccessFolder[$item] = getDirFiles(__APP_ROOT__.'/app/DataAccess/'.$item);

}
$result = array();
foreach($classDataAccessFolder as $DaFolder=>$DAFile)
{
    foreach($DAFile as $r){
        $dataAccessFiles[$r] = $DaFolder.'\\'.$r;
    }
}

foreach($dataAccessFiles as $key=>$dataAccessFile){
    $contentDataAccess = preg_replace('/.php/','',$dataAccessFile);
    $containerDataAccess = preg_replace('/.php/','',$key);
    $container[$containerDataAccess] = function ($container) use ($contentDataAccess){
        $classDataAccess =  '\\App\\DataAccess\\'.$contentDataAccess ;
        return new $classDataAccess($container);
    };
}


$GLOBALS['container'] = $container;
$GLOBALS['app'] = $app;
$GLOBALS['settings'] = $container['settings'];



return $container;