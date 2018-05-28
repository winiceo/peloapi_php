<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/11/30
 * Time: 下午8:21
 */

namespace App\Service;


use App\Helpers\CoinHelpers;
use Leven\App;
use Leven\Auth;
use Leven\Parse;
use Parse\ParseUser;

class ParseService
{
    use CoinHelpers;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct()
    {
        $this->container = App::getContainer();

        Parse::self();
    }

    public function register($cuser)
    {
       // $cuser=Auth::getUser()->toArray();





        $user = new ParseUser();
        $user->set("username", $cuser['username']);
        $user->set("password", md5('beeotc_'.$cuser['id']));
        $user->set("user_id", $cuser['id']);



        try {
            $user->signUp();
            $currentUser=ParseUser::getCurrentUser();
            dump($currentUser->_encode());
            return $user->_encode();
            // Hooray! Let them use the app now.
        } catch (ParseException $ex) {

            dump($ex);
            // Show the error message somewhere and let the user try again.
            echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
        }

    }

}