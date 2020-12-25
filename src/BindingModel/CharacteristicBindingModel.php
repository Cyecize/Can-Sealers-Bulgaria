<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 4:47 PM
 */

namespace App\BindingModel;

use Symfony\Component\Validator\Constraints as Assert;

class CharacteristicBindingModel
{
    /**
     * @Assert\NotBlank()
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="30")
     */
    private $icon;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="50")
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $body;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

}