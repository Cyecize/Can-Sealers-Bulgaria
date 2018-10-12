<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/10/2018
 * Time: 11:16 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductCategory;
use AppBundle\Utils\Page;
use AppBundle\Utils\Pageable;
use Doctrine\ORM\EntityManagerInterface;

class ProductServiceImpl implements ProductService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ProductRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $productRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->productRepo = $em->getRepository(Product::class);
    }

    public function findOneById(int $id = null, bool $showHidden = false): ?Product
    {
        if ($showHidden)
            return $this->productRepo->find($id);
        else
            return $this->productRepo->findOneBy(array('id' => $id, 'hidden' => false));
    }

    public function findByCategoryRecursive(ProductCategory $category, Pageable $pageable): Page
    {
        $cats = $category->getChildrenCategoriesRecursive();
        $cats[] = $category;
        return $this->productRepo->findByCategories($cats, $pageable);
    }

    public function findByCategory(ProductCategory $category, Pageable $pageable): Page
    {
        return $this->productRepo->findByCategory($category, $pageable);
    }

    public function findByProductType(string $productType): array
    {
        return $this->productRepo->findBy(array('productType' => $productType, 'hidden' => false));
    }

    public function findAll(Pageable $pageable, bool $showHidden = false): Page
    {
        return $this->productRepo->findAllPage($pageable, $showHidden);
    }

    public function all(): array
    {
        return $this->productRepo->findAll();
    }

}