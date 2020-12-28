<?php


namespace App\ViewModel;


use App\Entity\Receipt;

class ReceiptDetailsViewModel
{
    /**
     * @var Receipt
     */
    private $product;

    public function __construct(Receipt $receipt)
    {
        $this->product = $receipt;
    }

    /**
     * @return Receipt
     */
    public function getProduct(): Receipt
    {
        return $this->product;
    }
}