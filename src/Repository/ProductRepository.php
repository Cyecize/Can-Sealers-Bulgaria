<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Utils\Page;
use App\Utils\Pageable;


/**
 * ProductRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param array $categories
     * @param Pageable $pageable
     * @param bool $showHidden
     * @return Page
     */
    public function findByCategories(array $categories, Pageable $pageable, bool $showHidden = false): Page
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb
            ->where($qb->expr()->in('p.category', '?1'))
            ->setParameter(1, $categories);
        if (!$showHidden)
            $this->addShowHidden($query);
        return new Page($query, $pageable);
    }

    /**
     * @param ProductCategory $category
     * @param Pageable $pageable
     * @param bool $showHidden
     * @return Page
     */
    public function findByCategory(ProductCategory $category, Pageable $pageable, bool $showHidden = false): Page
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb
            ->where('p.category = :category')
            ->setParameter('category', $category)
            ->orderBy('p.id', 'DESC');
        if (!$showHidden)
            $this->addShowHidden($query);
        return new Page($query, $pageable);
    }

    /**
     * @param Pageable $pageable
     * @param bool $showHidden
     * @return Page
     */
    public function findAllPage(Pageable $pageable, bool $showHidden): Page
    {
        $qb = $this->createQueryBuilder('p');
        if (!$showHidden)
            $this->addShowHidden($qb);
        return new Page($qb, $pageable);
    }

    private function addShowHidden($query)
    {
        $query
            ->andWhere('p.hidden = FALSE');//TODO TEST THIS
    }
}