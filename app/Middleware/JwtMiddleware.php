<?php

namespace App\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Interfaces\RouterInterface;
use Slim\Middleware\JwtAuthentication;

class JwtMiddleware implements MiddlewareInterface
{


    /**

     */
    protected $container;

    /**
     * Constructor.
     *
     * @param RouterInterface $router
     * @param Sentinel        $sentinel
     */
    public function __construct($container)
    {
        $this->container = $container;

    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
         $mw =new JwtAuthentication([
            "secret" => $this->container->get('secret-key'),
            "secure" => false,
            "algorithm" => [ "HS256" ],
            "attribute" => "jwt",
             "callback" => function ($request, $response, $arguments)   {
             echo 555;
                 $this->container["jwt"] = $arguments["decoded"];
                 return true;

             }
        ]);

        return $mw;


    }
}
