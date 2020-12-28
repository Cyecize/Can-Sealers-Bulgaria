<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 2:30 PM
 */

namespace App\BindingModel;

use Symfony\Component\Validator\Constraints as Assert;

class EditProductBindingModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="40")
     */
    private $name;

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


    private $image;

    public function __construct()
    {
        $this->price = 0;
        $this->hidden = false;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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