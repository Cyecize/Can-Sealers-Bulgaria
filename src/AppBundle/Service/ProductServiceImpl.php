<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/10/2018
 * Time: 11:16 AM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\CreateProductBindingModel;
use AppBundle\BindingModel\EditProductBindingModel;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductCategory;
use AppBundle\Utils\ModelMapper;
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

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    /**
     * @var FileService
     */
    private $fileService;

    public function __construct(EntityManagerInterface $em, ModelMapper $modelMapper, FileService $fileService)
    {
        $this->entityManager = $em;
        $this->productRepo = $em->getRepository(Product::class);
        $this->modelMapper = $modelMapper;
        $this->fileService = $fileService;
    }

    public function editProduct(Product $product, EditProductBindingModel $bindingModel, ProductCategory $category): Product
    {
        $product = $this->modelMapper->merge($bindingModel, $product, true);
        $this->modelMapper->merge($bindingModel, $product);
        $product->setCategory($category);
        if ($bindingModel->getImage() != null) {
            $this->fileService->removeFile(substr($product->getImgPath(), 1));
            $product->setImgPath($this->fileService->uploadProductImage($bindingModel->getImage()));
        }
        return $this->save($product);
    }

    public function createProduct(CreateProductBindingModel $bindingModel, ProductCategory $category): Product
    {
        $product = new Product();
        $product->setCategory($category);
        $product = $this->modelMapper->merge($bindingModel, $product);
        $product->setImgPath($this->fileService->uploadProductImage($bindingModel->getImage()));
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }

    public function findOneById(int $id = null, bool $showHidden = false): ?Product
    {
        if ($showHidden)
            return $this->productRepo->find($id);
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

    private function save(Product $product): Product
    {
        $this->entityManager->merge($product);
        $this->entityManager->flush();
        return $product;
    }
}