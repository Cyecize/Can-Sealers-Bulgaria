<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/15/2018
 * Time: 1:32 PM
 */

namespace App\Utils;


use App\Constants\Config;
use App\Entity\ProductCategory;


class TwigUtil
{

    public function __construct()
    {
    }

    public function errorToArray($error = ""): array
    {
        $str = str_replace('</ul>', '', str_replace('<ul>', '', $error));
        $str = str_replace('<li>', ' ', str_replace('</li>', '', $str));
        return explode(' ', $str);
    }

    public function getCategoryName(ProductCategory $category, string $locale){
        switch ($locale){
            case Config::COOKIE_EN_LANG:
                return $category->getLatinName();
            default:
                return $category->getCategoryName();
        }
    }

}