<?php
namespace App\Middleware;

use Leven\Facades\Auth;

use Leven\Interfaces\_Middleware;

class TokenMiddleWare extends _Middleware
{



    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param callable                                 $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next  )
    {

        $mw =new JwtAuthentication([
            "secret" => "234324",
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

        $response = $next($request, $response);


        return $response;
    }
}
