<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="product_name", type="string", length=40)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="img_path", type="string", length=255)
     */
    private $imgPath;

    /**
     * @var string
     * @ORM\Column(name="video_path", type="string", length=255, nullable=true)
     */
    private $videoPath;

    /**
     * @var string
     * @ORM\Column(name="product_description", type="text", nullable=true)
     */
    private $productDescription;

    /**
     * @var string
     * @ORM\Column(name="product_description_en", type="text", nullable=true)
     */
    private $productDescriptionEn;

    /**
     * @var double
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var bool
     * @ORM\Column(name="tax_included", type="boolean", options={"default": 0})
     */
    private $taxIncluded;

    /**
     * @var bool
     * @ORM\Column(name="hidden", type="boolean", options={"default":0})
     */
    private $hidden;

    /**
     * @var ProductCategory
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCategory", inversedBy="products" , fetch="EAGER")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->price = 0.0;
        $this->hidden = false;
        $this->taxIncluded = false;
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    /**
     * @param string $imgPath
     */
    public function setImgPath(?string $imgPath): void
    {
        $this->imgPath = $imgPath;
    }

    /**
     * @return string
     */
    public function getVideoPath(): ?string
    {
        return $this->videoPath;
    }

    /**
     * @param string|null $videoPath
     */
    public function setVideoPath(?string $videoPath): void
    {
        $this->videoPath = $videoPath;
    }

    /**
     * @return string
     */
    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    /**
     * @param string|null $productDescription
     */
    public function setProductDescription(?string $productDescription): void
    {
        $this->productDescription = $productDescription;
    }

    /**
     * @return string
     */
    public function getProductDescriptionEn(): ?string
    {
        return $this->productDescriptionEn;
    }

    /**
     * @param string|null $productDescriptionEn
     */
    public function setProductDescriptionEn(?string $productDescriptionEn): void
    {
        $this->productDescriptionEn = $productDescriptionEn;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * @return bool
     */
    public function isTaxIncluded(): bool
    {
        return $this->taxIncluded;
    }

    /**
     * @param bool $taxIncluded
     */
    public function setTaxIncluded(bool $taxIncluded): void
    {
        $this->taxIncluded = $taxIncluded;
    }

    /**
     * @return ProductCategory
     */
    public function getCategory(): ProductCategory
    {
        return $this->category;
    }

    /**
     * @param ProductCategory $category
     */
    public function setCategory(ProductCategory $category): void
    {
        $this->category = $category;
    }

}

