<?php
/**
 * Created by PhpStorm.
 * User: Cyecize
 * Date: 11/15/2017
 * Time: 12:54 AM
 */

namespace App\Constants;


class Constants
{
    public static $galleryPath = "product-gallery" . DIRECTORY_SEPARATOR. "galleries" . DIRECTORY_SEPARATOR; // productId/imgName.jpg

    public static $categoriesPath = "product-gallery/categories/";

    public const INVALID_IMAGE_MSG = "Invalid Image Type or Size > 2MB";
}