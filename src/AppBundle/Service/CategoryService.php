<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/9/2018
 * Time: 2:52 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\ProductCategory;

interface CategoryService
{
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