<?php
namespace App\Module\Hello;


use Leven\Application;
use Leven\Interfaces\AbstractModule;

class Module extends AbstractModule
{
    public function initRoutes(Application $app)
    {
        $app->get('/hello/{name}', function ($request, $response) {
            return $this->view->render($response, '.index');
        })->setName('module.hello');
    }
}