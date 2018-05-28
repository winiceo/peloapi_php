<?php

namespace Leven\Facades;

use Leven\Services\AuthService;
use SlimFacades\Facade;


class Response extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'response';
    }
}