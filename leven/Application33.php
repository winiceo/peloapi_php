<?php

declare(strict_types=1);

namespace Leven;


use Slim\App;
use DI\ContainerBuilder;

class Application extends App
{
    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * Constructor.
     *
     * @param string $environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
        $this->rootDir = $this->getRootDir();
        require  $this->rootDir.'/leven/Functions/general_helpers.php';

        C('root', $this->rootDir);
        C('templates', $this->rootDir . '/templates');
        C('cache', $this->rootDir . '/var/cache/twig');
        C('environment', $environment);

//        $settings=require($this->getBootstrap()."/settings.php");
//        $configuration = new Configuration($settings);
//
//        //$configuration->setDefinitions($this->loadConfiguration());
//        C($configuration->getDefinitions());

       // $configuration->setDefinitions($this->getBootstrap() . '/container.php');
//
//        $container=ContainerBuilder::build($configuration);
//
//        dump($container->get("settings"));
//        exit;
//        parent::__construct(ContainerBuilder::build($configuration));


        $containerBuilder = new ContainerBuilder;
        $config=$this->loadConfiguration();
       // $containerBuilder->addDefinitions($this->getBootstrap() . '/settings.php' );
        C($config);
        $containerBuilder->addDefinitions(__DIR__ . '/definitions.php');



        $this->configureContainer($containerBuilder);
        $container = $containerBuilder->build();

        parent::__construct($container);


        require  $this->rootDir .'/leven/Functions/helpers.php';

       // $container = $this->getContainer();

       // $cc=$container->get($container);
       // dump($cc);

        $this->registerControllers($container);

        $this->registerHandlers();
        $this->loadMiddleware();
        $this->loadRoutes();
    }

    protected function configureContainer(ContainerBuilder $builder)
    {
        $rootDir = $this->rootDir;
        $container = $this->getContainer();



        $builder->addDefinitions($this->getBootstrap() . '/container.php');

    }


    public function getCacheDir()
    {
        return $this->getRootDir() . '/var/cache/' . $this->environment;
    }

    public function getBootstrap()
    {
        return $this->getRootDir() . '/bootstrap';
    }


    public function getConfigurationDir()
    {
        return $this->getRootDir() . '/config';
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function getLogDir()
    {
        return $this->getRootDir() . '/var/logs';
    }

    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $this->rootDir = dirname(__DIR__);
        }

        return $this->rootDir;
    }


    protected function loadConfiguration()
    {

        $configuration = [

                "settings"=>[]

        ];
        $app=$this;
        $env = new \Leven\Helpers\Env();

        /*Dynamic containers in services*/
        $config_dir = scandir($this->rootDir.'/config/');
        $ex_config_folders = array('..', '.');
        $filesInConfig =  array_diff($config_dir,$ex_config_folders);
        if (!isset($configs)) {
            $configs = array();
        }
        $i=0;
        foreach($filesInConfig as $config_file){
            $file[$i] = include_once  $this->rootDir.'/config/'.$config_file;
            if(is_array($file[$i])){
                $configs = array_merge($configs, $file[$i]);
                $i++;
            }


        }

        $configuration['settings'] = $configs;

        return $configuration;
    }


    protected function loadMiddleware()
    {
        $app = $this;
        $container = $this->getContainer();
        require $this->getRootDir() . '/leven/middlewares.php';
    }

    protected function loadRoutes()
    {
        $app = $this;
        $container = $this->getContainer();
//        require $this->getConfigurationDir() . '/apiRoute.php';
//        require $this->getConfigurationDir() . '/webRoutes.php';

        $route = new Route($app);

        $files =  getDirFiles($this->rootDir.'/app/Routes/');
        /** Route Partial Loadup =================================================== */
        foreach ($files as $partial) {
            $file = $this->rootDir.'/app/Routes/'.$partial;
            $filse[] = $file;
            if ( ! file_exists($file))
            {
                $msg = "Route partial [{$partial}] not found.";
            }
            include $file;
        }

    }

    protected function registerControllers($container)
    {
        // $container = $this->getContainer();
        if (file_exists($this->getConfigurationDir() . '/controllers.php')) {
            $controllers = require $this->getConfigurationDir() . '/controllers.php';
            foreach ($controllers as $key => $class) {
                $container->set($key, $class);
            }
        }
    }

    protected function registerHandlers()
    {
        $container = $this->getContainer();
        require $this->getBootstrap() . '/handlers.php';
    }
}
