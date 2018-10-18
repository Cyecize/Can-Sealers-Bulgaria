<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/9/2018
 * Time: 2:52 PM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\CategoryBindingModel;
use AppBundle\Entity\ProductCategory;
use AppBundle\Exception\IllegalArgumentException;


interface CategoryService
{
    /**
     * @param ProductCategory $category
     * @param CategoryBindingModel $bindingModel
     * @return ProductCategory
     * @throws IllegalArgumentException
     */
    function editCategory(ProductCategory $category, CategoryBindingModel $bindingModel) : ProductCategory;

    /**
     * @param CategoryBindingModel $bindingModel
     * @return ProductCategory
     * @throws IllegalArgumentException
     */
    function createCategory(CategoryBindingModel $bindingModel): ProductCategory;

    /**
     * @param int $id
     * @return ProductCategory|null
     */
    public function findOneById(int $id): ?ProductCategory;

    /**
     * @param string $name
     * @return ProductCategory|null
     */
    public function findOneByName(string $name): ?ProductCategory;

    /**
     * @return ProductCategory[]
     */
    public function findMain(): array;

    /**
     * @return ProductCategory[]
     */
    public function findAll(): array;
}