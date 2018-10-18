<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 10:44 AM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class CreateProductBindingModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="40")
     */
    private $productName;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^Product$|^Receipt$/")
     */
    private $productType;

    /**
     * @Assert\Length(max="2000", maxMessage="Max Length is 2000")
     */
    private $productDescription;

    /**
     * @Assert\Regex(pattern="/^-?[0-9.]+$/", message="Invalid price")
     */
    private $price;

    /**
     * boolean
     */
    private $hidden;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[0-9]+$/")
     */
    private $categoryId;

    /**
     * @Assert\NotNull(message="Select image")
     * @Assert\File(
     *     maxSize="2M", maxSizeMessage="File size more than 2M",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage="Please upload a valid jpg"
     * )
     */
    private $image;

    public function __construct()
    {
        $this->price = 0;
    }

    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param mixed $productName
     */
    public function setProductName($productName): void
    {
        $this->productName = $productName;
    }

    /**
     * @return mixed
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * @param mixed $productType
     */
    public function setProductType($productType): void
    {
        $this->productType = $productType;
    }

    /**
     * @return mixed
     */
    public function getProductDescription()
    {
        return $this->productDescription;
    }

    /**
     * @param mixed $productDescription
     */
    public function setProductDescription($productDescription): void
    {
        $this->productDescription = $productDescription;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getHidden()
    {
        return $this->hidden != null ? true : false;
    }

    /**
     * @param mixed $hidden
     */
    public function setHidden($hidden): void
    {
        if ($hidden == null)
            $hidden = false;
        if ($hidden == "on")
            $hidden = true;
        $this->hidden = $hidden;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }
}