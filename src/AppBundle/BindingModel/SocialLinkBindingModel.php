<?php

namespace AppBundle\BindingModel;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SocialLink
 *
 * @ORM\Table(name="social_links")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SocialLinkRepository")
 */
class SocialLinkBindingModel
{
    /**
     * @Assert\NotBlank()
     */
    private $id;

    /**
     * @Assert\NotBlank()
     */
    private $icon;

    /**
     * @Assert\NotBlank()
     */
    private $link;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }
}

