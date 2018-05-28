<?php

namespace Leven\Interfaces;
use Leven\Application;
use Slim\Container;
use Composer\Autoload\ClassLoader;

abstract class AbstractModule
{


    /**
     * Get config array for this module
     * @return array
     */
    public function getModuleConfig()
    {
        return [];
    }


    /**
     * Set class maps for class loader to autoload classes for this module
     * @param Container $container
     * @return void
     */
    public function initDependencies(Container $container)
    {

    }

    /**
     * Initiate app middleware, route middleware should go in load() with routes
     * @param App $app
     * @return void
     */
    public function initMiddleware(Application $app)
    {

    }

    /**
     * Load is run last, when config, dependencies, etc have been initiated
     * Routes ought to go here
     * @param App $app
     * @return void
     */
    public function initRoutes(Application $app)
    {

    }
}