<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductCategory
 *
 * @ORM\Table(name="product_categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductCategoryRepository")
 */
class ProductCategory
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="category_name", type="string", length=60)
     */
    private $categoryName;

    /**
     * @var string
     * @ORM\Column(name="latin_name", type="string", length=50)
     */
    private $latinName;

    /**
     * @var Product[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="category")
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductCategory", inversedBy="subcategories")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * @var ProductCategory
     */
    private $parentCategory;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductCategory", mappedBy="parentCategory")
     * @var ProductCategory[]
     */
    private $subcategories;

    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @param string $categoryName
     */
    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return string
     */
    public function getLatinName(): string
    {
        return $this->latinName;
    }

    /**
     * @param string $latinName
     */
    public function setLatinName(string $latinName): void
    {
        $this->latinName = $latinName;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    /**
     * @return ProductCategory
     */
    public function getParentCategory(): ?ProductCategory
    {
        return $this->parentCategory;
    }

    /**
     * @param ProductCategory $parentCategory
     */
    public function setParentCategory(ProductCategory $parentCategory): void
    {
        $this->parentCategory = $parentCategory;
    }

    /**
     * @return ProductCategory[]
     */
    public function getSubcategories()
    {
        return $this->subcategories;
    }

    /**
     * @param ProductCategory[] $subcategories
     */
    public function setSubcategories(array $subcategories): void
    {
        $this->subcategories = $subcategories;
    }

    /**
     * @return ProductCategory[]
     */
    public function getChildrenCategoriesRecursive(): array
    {
        $res = array();
        foreach ($this->subcategories as $childCategory) {
            $res = array_merge($childCategory->getChildrenCategoriesRecursive(), $res);
        }
        $res = array_merge($this->subcategories->toArray(), $res);
        return $res;
    }
}

