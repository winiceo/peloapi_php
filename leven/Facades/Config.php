<?php
/**
 * User: zhangshize
 * Date: 2016/12/30
 * Time: 下午 3:34
 */

namespace Leven\Facades;

use Leven\Services\AuthService;
use SlimFacades\Facade;

class Config extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'config';
    }
}