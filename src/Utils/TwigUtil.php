<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/15/2018
 * Time: 1:32 PM
 */

namespace App\Utils;


use App\Constants\Config;
use App\Entity\Product;
use App\Entity\ProductCategory;


class TwigUtil
{

    public function __construct()
    {
    }

    public function getEnv(): string {
        return $_ENV[Config::ENV_APP_ENV];
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
            case Config::COOKIE_BG_LANG:
                return $category->getCategoryName();
            default:
                throw new \Exception("Cannot get category name, locale not found!");
        }
    }

    public function getProductDescription(Product $product, string $locale) {
        switch ($locale){
            case Config::COOKIE_EN_LANG:
                return $product->getProductDescriptionEn();
            case Config::COOKIE_BG_LANG:
                return $product->getProductDescription();
            default:
                throw new \Exception("Cannot get product description, locale not found!");
        }
    }
}