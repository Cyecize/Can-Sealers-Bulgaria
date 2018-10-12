<?php
/**
 * Created by PhpStorm.
 * User: Cyecize
 * Date: 12/7/2017
 * Time: 9:42 PM
 */

namespace AppBundle\Utils;


class RandomCreator
{
    public static function createSecretCode() {
        $time = time();
        $time-= 20;
        $time = $time * random_int(2,4);
        return $time - 23 + random_int(24, 2555);
    }
}