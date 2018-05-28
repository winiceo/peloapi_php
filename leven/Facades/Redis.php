<?php
namespace Leven\Facades;

use Leven\Services\AuthService;
use SlimFacades\Facade;

class Redis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'redis';
    }
}