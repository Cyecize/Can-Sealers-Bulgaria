<?php

namespace App\Entity;

use App\Repository\ReceiptRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReceiptRepository::class)
 * @ORM\Table(name="receipts")
 */
class Receipt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="receipt_name", length=40)
     */
    private $name;

    /**
     * @ORM\Column(type="string", name="img_path", length=255)
     */
    private $imgPath;

    /**
     * @ORM\Column(type="text", name="receipt_description", nullable=true)
     */
    private $receiptDescription;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $hidden;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(string $imgPath): self
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    public function getReceiptDescription(): ?string
    {
        return $this->receiptDescription;
    }

    public function setReceiptDescription(?string $receiptDescription): self
    {
        $this->receiptDescription = $receiptDescription;

        return $this;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }
}
