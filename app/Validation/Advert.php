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


trait Advert
{
    


    function inputCheck(Request $request)
    {

        $validator = Validation::createValidator();
        $coin_type = $request->getParam('coin_type');
        $trade_type = $request->getParam('trade_type');
        $margin = $request->getParam('margin');
        $price = $request->getParam('price');
        $min_amount = $request->getParam('min_amount');
        $max_amount = $request->getParam('max_amount');

        //dump($request->getParams());

        $violations = [];
        $violations[] = $validator->validate($coin_type, array(
            new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '货币类型')))),
            new Assert\Type(array(
                'type'    => 'int',
                'message' => '货币类型必须为数字',
            ))
        ));
        $violations[] = $validator->validate($trade_type, array(
            new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '交易类型')))),
            new Assert\Type(array(
                'type'    => 'int',
                'message' => '交易类型必须为数字',
            ))
        ));
        $violations[] = $validator->validate($margin, array(
            new Assert\NotBlank(array('message' => Lang::trans('%name% not blank', array('%name%' => '溢价比例')))),
            new Assert\Type(array(
                'type'    => 'double',
                'message' => '交易类型必须为数字',
            ))
        ));


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
