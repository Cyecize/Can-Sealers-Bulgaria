<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 4:27 PM
 */

namespace AppBundle\BindingModel;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryBindingModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="50")
     */
    private $categoryName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="50")
     */
    private $latinName;

    /**
     * @Assert\Regex(pattern="/^[0-9]*$/")
     */
    private $parentCatId;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @param mixed $categoryName
     */
    public function setCategoryName($categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return mixed
     */
    public function getLatinName()
    {
        return $this->latinName;
    }

    /**
     * @param mixed $latinName
     */
    public function setLatinName($latinName): void
    {
        $this->latinName = $latinName;
    }

    /**
     * @return mixed
     */
    public function getParentCatId()
    {
        return $this->parentCatId;
    }

    /**
     * @param mixed $parentCatId
     */
    public function setParentCatId($parentCatId): void
    {
        $this->parentCatId = $parentCatId;
    }
}