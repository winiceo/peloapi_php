<?php

namespace Leven\Facades;

use Leven\Services\AuthService;
use SlimFacades\Facade;
class Log extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'monolog';
    }
}