<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/10/2018
 * Time: 11:13 AM
 */

namespace App\Service;

use App\BindingModel\CreateProductBindingModel;
use App\BindingModel\EditProductBindingModel;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Utils\Page;
use App\Utils\Pageable;

interface ProductService
{
    /**
     * @param Product $product
     * @param EditProductBindingModel $bindingModel
     * @param ProductCategory $category
     * @return Product
     */
    public function editProduct(Product $product, EditProductBindingModel $bindingModel, ProductCategory $category) : Product;

    /**
     * @param CreateProductBindingModel $bindingModel
     * @param ProductCategory $category
     * @return Product
     */
    public function createProduct(CreateProductBindingModel $bindingModel, ProductCategory $category) : Product;

    /**
     * @param int|null $id
     * @param bool $showHidden
     * @return Product|null
     */
    public function findOneById(int $id = null, bool $showHidden = false): ?Product;

    /**
     * @param ProductCategory $category
     * @param Pageable $pageable
     * @return Page
     */
    public function findByCategoryRecursive(ProductCategory $category, Pageable $pageable): Page;

    /**
     * @param ProductCategory $category
     * @param Pageable $pageable
     * @return Page
     */
    public function findByCategory(ProductCategory $category, Pageable $pageable) : Page;

    /**
     * @param string $productType
     * @return Product[]
     */
    public function findByProductType(string $productType): array;

    /**
     * @param Pageable $pageable
     * @param bool $showHidden
     * @return Page
     */
    public function findAll(Pageable $pageable, bool $showHidden = false): Page;

    /**
     * @return Product[]
     */
    public function all() : array ;
}