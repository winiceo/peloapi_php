<?php
namespace Leven\Facades;

use Leven\Services\AuthService;
use SlimFacades\Facade;

class Request extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'request';
    }
}