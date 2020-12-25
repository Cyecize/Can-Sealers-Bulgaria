<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/10/2018
 * Time: 4:15 PM
 */

namespace App\ViewModel;

use App\Entity\Gallery;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Utils\Page;

class ProductDetailsViewModel
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var Gallery
     */
    private $gallery;

    /**
     * @var ProductCategory[]
     */
    private $categories;

    /**
     * @var Product[]
     */
    private $similar;

    public function __construct(Product $product, $categories, Page $similar,Gallery $gallery = null)
    {
        $this->product = $product;
        $this->gallery = $gallery;
        $this->categories = $categories;
        $this->similar = $similar->getElements();
    }

    /**
     * @return ProductCategory[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return Gallery
     */
    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return Product[]
     */
    public function getSimilar(): array
    {
        return $this->similar;
    }
}