<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/9/2018
 * Time: 2:54 PM
 */

namespace App\Service;

use App\BindingModel\CategoryBindingModel;
use App\Entity\ProductCategory;
use App\Exception\IllegalArgumentException;
use App\Repository\ProductCategoryRepository;
use App\Utils\ModelMapper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;


class CategoryServiceImpl implements CategoryService
{
    private const NAME_TAKEN_METHOD_NAME = "nameTaken";

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProductCategoryRepository|ObjectRepository
     */
    private $categoryRepo;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    public function __construct(EntityManagerInterface $em, ModelMapper $modelMapper)
    {
        $this->entityManager = $em;
        $this->categoryRepo = $em->getRepository(ProductCategory::class);
        $this->modelMapper = $modelMapper;
    }

    public function editCategory(ProductCategory $category, CategoryBindingModel $bindingModel): ProductCategory
    {
        if (!$this->isNewCatNameUnique($category, $bindingModel->getCategoryName()) || !$this->isNewCatNameUnique($category, $bindingModel->getLatinName()))
            throw new IllegalArgumentException(self::NAME_TAKEN_METHOD_NAME);
        $category = $this->modelMapper->merge($bindingModel, $category);
        $category->setParentCategory($this->findOneById(intval($bindingModel->getParentCatId())));
        $this->entityManager->merge($category);
        $this->entityManager->flush();
        return $category;
    }

    public function createCategory(CategoryBindingModel $bindingModel): ProductCategory
    {
        $category = $this->modelMapper->map($bindingModel, ProductCategory::class);
        if (!$this->isNewCatNameUnique($category, $bindingModel->getCategoryName()) || !$this->isNewCatNameUnique($category, $category->getLatinName()))
            throw new IllegalArgumentException(self::NAME_TAKEN_METHOD_NAME);
        $category->setParentCategory($this->findOneById(intval($bindingModel->getParentCatId())));
        $this->entityManager->persist($category);
        $this->entityManager->flush();
        return $category;
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

    //private
    private function isNewCatNameUnique(ProductCategory $category, string $name)
    {
        $dbCat = $this->findOneByName($name);
        return $dbCat == null || $dbCat->getId() == $category->getId();
    }
}