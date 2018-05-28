<?php

namespace Leven;


use Psr\Container\ContainerInterface;
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
        C('environment', $environment);


        $containerBuilder = new ContainerBuilder;
        C('root', $this->rootDir);
        C('templates', $this->rootDir . '/templates');
        C('cache', $this->rootDir . '/var/cache/twig');
        C('environment', $environment);
        C($this->loadConfiguration());

        $containerBuilder->addDefinitions($this->loadConfiguration());

        $containerBuilder->addDefinitions($this->getConfigurationDir() . '/container.php');
        $containerBuilder->addDefinitions($this->getConfigurationDir() . '/handlers.php');
        $containerBuilder->useAnnotations(true);

        $container = $containerBuilder->build();
        //$container->set('api.test.controller',    'App\Controller\Api\TestController');

        parent::__construct($container);
        $this->registerControllers($container);
        $container->set('app', $this);

        // $this->configureContainer($containerBuilder);


        $this->registerHandlers();
        $this->loadMiddleware();

        $this->loadRoutes();
    }

    protected function configureContainer(ContainerBuilder $builder)
    {
        $rootDir = $this->rootDir;
        $container = $this->getContainer();

        //require $this->getConfigurationDir() . '/container.php';
        $builder->addDefinitions($this->getConfigurationDir() . '/container.php');

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
        $app = $this;
//        $configuration = [
//            'settings' => require $this->getConfigurationDir() . '/slim.php'
//        ];

        $configuration = require $this->getConfigurationDir() . '/config.php';

        if (file_exists($this->getConfigurationDir() . '/services.' . $this->getEnvironment() . '.php')) {
            $configuration['settings'] += require $this->getConfigurationDir() . '/services.' . $this->getEnvironment() . '.php';
        } else {
            $configuration['settings'] += require $this->getConfigurationDir() . '/services.php';
        }

        return $configuration;
    }


    protected function loadMiddleware()
    {
        $app = $this;
        $container = $this->getContainer();
        require $this->getRootDir() . '/bootstrap/middlewares.php';
    }

    protected function loadRoutes()
    {
        $app = $this;
        $container = $this->getContainer();
        require $this->getConfigurationDir() . '/apiRoute.php';
        require $this->getConfigurationDir() . '/webRoutes.php';
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
        require $this->getConfigurationDir() . '/handlers.php';
    }
}
