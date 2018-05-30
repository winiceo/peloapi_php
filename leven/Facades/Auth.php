<?php
/**
 * User: zhangshize
 * Date: 2016/12/30
 * Time: 下午 3:34
 */

namespace Leven\Facades;

/**
 * Class View
 * If you want to use this facades, you should set a 'view' service in the
 * container.
 * @package Leven
 */
class Auth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'auth';
    }
}