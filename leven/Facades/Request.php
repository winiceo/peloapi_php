<?php
/**
 * User: zhangshize
 * Date: 2016/12/30
 * Time: 下午 3:34
 */

namespace Leven\Facades;


class Request extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'request';
    }
}