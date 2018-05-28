<?php
/**
 * Created by PhpStorm.
 * User: genv
 * Date: 2017/12/13
 * Time: 上午12:06
 */

namespace App\Validator;

use Leven\Lang;
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
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        $violations = [];
        $violations[] = $validator->validate($username, array(

            new Assert\Length(array(
                'max' => 25,
                'maxMessage' => Lang::trans('max %name%', array('%name%' => 25)))),
            new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '用户名')))),

        ));

        $violations[] = $validator->validate($email, new Assert\Email(array('message' => '邮箱不正确')));
        $violations[] = $validator->validate($password, array(
                new Assert\Length(array('min' => 6, 'max' => 25,
                    'exactMessage' => '密码长度为6-25',
                    'minMessage' => '密码最小长度为6',
                    'maxMessage' => '密码最大长度为25'
                )),
                new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '密码')))),
            )
        );
//
//        $constraint = new Assert\Collection(array(
//            // the keys correspond to the keys in the input array
//            'username' => array(
//                new Assert\Length(array(
//                    'max' => 25,
//                    'maxMessage' => $this->translator->trans('max %name%', array('%name%' => 25)))),
//                new Assert\NotBlank(array('message' => $this->translator->trans('%name% not blank', array('%name%' => '名称')))),
//
//            ),
//            'email' => new Assert\Email(array('message' => '邮箱不正确')),
//            'password' => array(
//                new Assert\Length(array('min' => 6, 'max' => 25,
//                    'exactMessage' => '密码长度为6-25',
//                    'minMessage' => '最小长度为6',
//                    'maxMessage' => '最大长度为25'
//                )),
//                new Assert\NotBlank(array('message' => $this->translator->trans('%name% not blank', array('%name%' => '密码')))),
//            )
//
//
//        ));
        // $violations = $validator->validate($request->getParams(), $constraint);

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
