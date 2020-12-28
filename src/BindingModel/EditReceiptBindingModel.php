<?php

namespace App\BindingModel;

use Symfony\Component\Validator\Constraints as Assert;

class EditReceiptBindingModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="40")
     */
    private $receiptName;

    /**
     * @Assert\Length(max="2000", maxMessage="Max Length is 2000")
     */
    private $receiptDescription;

    /**
     * boolean
     */
    private $hidden;

    private $image;

    /**
     * @return mixed
     */
    public function getReceiptName()
    {
        return $this->receiptName;
    }

    /**
     * @param mixed $receiptName
     */
    public function setReceiptName($receiptName): void
    {
        $this->receiptName = $receiptName;
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