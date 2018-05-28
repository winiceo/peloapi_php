<?php
/**
 * Created by PhpStorm.
 * User: afshin
 * Date: 11/24/17
 * Time: 1:08 PM
 */

namespace Leven\Facades;

use Leven\Services\FileService;
use SlimFacades\Facade;
class File extends Facade
{
    /**
     * @param Core\Services\AuthService\AuthService
     * @return AuthService
    */
    protected static function getFacadeAccessor()
    {
        return 'FileService';
    }
}
