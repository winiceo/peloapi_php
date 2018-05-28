<?php

namespace App\Controller\Api\V2;
use App\Controller\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class MyControllerClassName extends Controller
{

    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'index');

    }
}