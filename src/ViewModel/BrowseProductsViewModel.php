<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/9/2018
 * Time: 3:16 PM
 */

namespace App\ViewModel;


use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Utils\Page;

class BrowseProductsViewModel
{
    /**
     * @var ProductCategory
     */
    private $category;

    /**
     * @var Product[]
     */
    private $products;

    /**
     * @var ProductCategory[]
     */
    private $categories;

    /**
     * @var Page
     */
    private $productPage;


    public function __construct(ProductCategory $category, Page $productPage,  $categories)
    {
        $this->category = $category;
        $this->products = $productPage->getElements();
        $this->productPage = $productPage;
        $this->categories = $categories;
    }

    /**
     * @return ProductCategory
     */
    public function getCategory(): ProductCategory
    {
        return $this->category;
    }

    /**
     * @return ProductCategory[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return Page
     */
    public function getProductPage(): Page
    {
        return $this->productPage;
    }

}