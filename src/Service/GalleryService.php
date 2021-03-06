<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/19/2018
 * Time: 10:46 AM
 */

namespace App\Service;


use App\Entity\Gallery;
use App\Entity\Product;

interface GalleryService
{
    /**
     * @param Product $product
     * @return Gallery
     */
    public function createIfNotExists(Product $product) : Gallery;

    /**
     * @param int $id
     * @return Gallery|null
     */
    public function findOneById(int $id) : ?Gallery;

    /**
     * @param Product $product
     * @return Gallery|null
     */
    public function findByProduct(Product $product) : ?Gallery;
}