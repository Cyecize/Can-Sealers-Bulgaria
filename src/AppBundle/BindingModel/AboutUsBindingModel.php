<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 3:07 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class AboutUsBindingModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $aboutUs;

    public function __construct()
    {
    }

    /**
     * @param mixed $aboutUs
     */
    public function setAboutUs($aboutUs): void
    {
        $this->aboutUs = $aboutUs;
    }

    /**
     * @return mixed
     */
    public function getAboutUs()
    {
        return $this->aboutUs;
    }
}