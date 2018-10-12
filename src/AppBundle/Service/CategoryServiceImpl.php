<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/9/2018
 * Time: 2:54 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\ProductCategory;
use Doctrine\ORM\EntityManagerInterface;

class CategoryServiceImpl implements CategoryService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ProductCategoryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $categoryRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->categoryRepo = $em->getRepository(ProductCategory::class);
    }

    public function findOneById(int $id): ?ProductCategory
    {
        return $this->categoryRepo->find($id);
    }

    public function findOneByName(string $name): ?ProductCategory
    {
        return $this->categoryRepo->findOneByName($name);
    }

    public function findMain(): array
    {
        return $this->categoryRepo->findBy(array('parentCategory' => null));
    }

    public function findAll(): array
    {
        return $this->categoryRepo->findAll();
    }
}