<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/12/13
 * Time: 上午12:06
 */

namespace App\Validation;


use Leven\Facades\Lang;
use Slim\Http\Request;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use DI\Annotation\Inject;


trait UserRegister
{

    protected $translator;

    function inputCheck(Request $request)
    {

        $validator = Validation::createValidator();
        $username = $request->getParam('username');
        $mobile = $request->getParam('mobile');
        $password = $request->getParam('password');

        $violations = [];
        $violations[] = $validator->validate($username, array(

            new Assert\Length(array(
                'max' => 25,
                'maxMessage' => Lang::trans('max %name%', array('%name%' => 25)))),
            new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '用户名')))),

        ));

        $violations[] = $validator->validate($mobile, array(
            new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '手机号')))),

        ));
        $violations[] = $validator->validate($password, array(
                new Assert\Length(array('min' => 6, 'max' => 25,
                    'exactMessage' => '密码长度为6-25',
                    'minMessage' => '密码最小长度为6',
                    'maxMessage' => '密码最大长度为25'
                )),
                new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '密码')))),
            )
        );

        $ret = [];
        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                foreach ($violation as $error) {
                    $ret[] = $error;
                }

            }
        }
//


        return $ret;
    }

    function forgetInputCheck(Request $request)
    {

        $validator = Validation::createValidator();
        $mobile = $request->getParam('mobile');
        $password = $request->getParam('password');

        $violations = [];


        $violations[] = $validator->validate($mobile, array(
            new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '手机号')))),

        ));
        $violations[] = $validator->validate($password, array(
                new Assert\Length(array('min' => 6, 'max' => 25,
                    'exactMessage' => '密码长度为6-25',
                    'minMessage' => '密码最小长度为6',
                    'maxMessage' => '密码最大长度为25'
                )),
                new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '密码')))),
            )
        );

        $ret = [];
        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                foreach ($violation as $error) {
                    $ret[] = $error;
                }

            }
        }
//


        return $ret;
    }

    function UpdatePasswordInputCheck(Request $request)
    {

        $validator = Validation::createValidator();
        $mobile = $request->getParam('mobile');
        $password = $request->getParam('password');
        $old_password = $request->getParam('old_password');

        $violations = [];


        $violations[] = $validator->validate($old_password, array(
            new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '旧密码')))),

        ));
        $violations[] = $validator->validate($password, array(
                new Assert\Length(array('min' => 6, 'max' => 25,
                    'exactMessage' => '密码长度为6-25',
                    'minMessage' => '密码最小长度为6',
                    'maxMessage' => '密码最大长度为25'
                )),
                new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '密码')))),
            )
        );

        $ret = [];
        if (0 !== count($violations)) {
            // there are errors, now you can show them
            foreach ($violations as $violation) {
                foreach ($violation as $error) {
                    $ret[] = $error;
                }

            }
        }
//


        return $ret;
    }


}
