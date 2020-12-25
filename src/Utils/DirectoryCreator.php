<?php
/**
 * Created by PhpStorm.
 * User: Cyecize
 * Date: 11/21/2017
 * Time: 9:54 PM
 */

namespace App\Utils;


use App\Constants\Constants;

class DirectoryCreator
{
    public static function createGalleryDirectory($productId){
        if(!is_dir(Constants::$galleryPath . $productId))
            mkdir(Constants::$galleryPath . $productId, 0777, true);
    }

    public static function createCategoryDirectory($categoryName){
        if (!is_dir(Constants::$categoriesPath . $categoryName))
            mkdir(Constants::$categoriesPath . $categoryName, 0777, true);
    }
}