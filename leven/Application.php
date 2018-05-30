<?php

declare(strict_types=1);

namespace Leven;

use DI\ContainerBuilder;
use Slim\App;

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
        require $this->rootDir . '/leven/Functions/general_helpers.php';

        C('environment', $environment);


        $containerBuilder = new ContainerBuilder;
        C('root', $this->rootDir);
        C('templates', $this->rootDir . '/templates');
        C('cache', $this->rootDir . '/var/cache/twig');
        C('environment', $environment);
        C($this->loadConfiguration());

        $containerBuilder->addDefinitions($this->loadConfiguration());
        $containerBuilder->addDefinitions($this->getBootstrap() . '/container.php');
        $containerBuilder->addDefinitions($this->getBootstrap() . '/handlers.php');
        $containerBuilder->useAnnotations(true);
        //$container = $containerBuilder->build();

        parent::__construct($containerBuilder->build());

        require $this->rootDir . '/leven/Functions/helpers.php';

        $container=$this->getContainer();
        $this->registerControllers($container);
        //$container->set('app', $this);


        //$//this->registerHandlers($container);
        $this->loadMiddleware();

        $this->loadRoutes();
    }


    public function getBootstrap()
    {
        return $this->getRootDir() . '/bootstrap';
    }

    public function getCacheDir()
    {
        return $this->getRootDir() . '/var/cache/' . $this->environment;
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
        return $this->getRootDir() . '/var/log';
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
        $app = $this;

        $configuration = require __DIR__ . '/config.php';

        if (file_exists($this->getConfigurationDir() . '/services.' . $this->getEnvironment() . '.php')) {
            $configuration['settings'] += require $this->getConfigurationDir() . '/services.' . $this->getEnvironment() . '.php';
        } else {
            $configuration['settings'] += require $this->getConfigurationDir() . '/services.php';
        }

        $app = $this;
        $env = new \Leven\Helpers\Env();

        /*Dynamic containers in services*/
        $config_dir = scandir($this->rootDir . '/config/');
        $ex_config_folders = array('..', '.');
        $filesInConfig = array_diff($config_dir, $ex_config_folders);
        if (!isset($configs)) {
            $configs = array();
        }
        $i = 0;
        foreach ($filesInConfig as $config_file) {
            $file[$i] = include_once $this->rootDir . '/config/' . $config_file;
            if (is_array($file[$i])) {
                $configs = array_merge($configs, $file[$i]);
                $i++;
            }
        }
        $configuration['settings'] += $configs;


        return $configuration;
    }


    protected function loadMiddleware()
    {
        $app = $this;
        $container = $this->getContainer();


        require $this->getBootstrap() . '/middleware.php';
    }

    protected function loadRoutes()
    {
        $app = $this;
        $container = $this->getContainer();
        $route = new Route($app);
        $files = getDirFiles($this->rootDir . '/app/Routes/');
        /** Route Partial Loadup =================================================== */
        foreach ($files as $partial) {
            $file = $this->rootDir . '/app/Routes/' . $partial;
            $filse[] = $file;
            if (!file_exists($file)) {
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

    protected function registerHandlers($container)
    {

        require $this->getBootstrap() . '/handlers.php';
    }
}
