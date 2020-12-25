<?php
/**
 * Created by PhpStorm.
 * User: Cyecize
 * Date: 12/4/2017
 * Time: 1:54 PM
 */

namespace App\Utils;


class DateFormatter
{
    public static function formatStandard(\DateTime $date){
        return date_format ( $date, "d/m/Y г. в  h:i ч." );
    }
}