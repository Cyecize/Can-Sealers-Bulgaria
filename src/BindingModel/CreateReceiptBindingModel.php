<?php

namespace App\BindingModel;

use Symfony\Component\Validator\Constraints as Assert;

class CreateReceiptBindingModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="40")
     */
    private $name;

    /**
     * @Assert\Length(max="2000", maxMessage="Max Length is 2000")
     */
    private $receiptDescription;

    /**
     * boolean
     */
    private $hidden;

    /**
     * @Assert\NotNull(message="Select image")
     * @Assert\File(
     *     maxSize="2M", maxSizeMessage="File size more than 2M",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage="Please upload a valid jpg"
     * )
     */
    private $image;

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
    public function getReceiptDescription()
    {
        return $this->receiptDescription;
    }

    /**
     * @param mixed $receiptDescription
     */
    public function setReceiptDescription($receiptDescription): void
    {
        $this->receiptDescription = $receiptDescription;
    }

    /**
     * @return mixed
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param mixed $hidden
     */
    public function setHidden($hidden): void
    {
        $this->hidden = $hidden;
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