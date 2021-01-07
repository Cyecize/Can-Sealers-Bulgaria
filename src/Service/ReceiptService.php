<?php

namespace App\Service;

use App\BindingModel\CreateReceiptBindingModel;
use App\BindingModel\EditReceiptBindingModel;
use App\Entity\Receipt;
use App\Utils\Page;
use App\Utils\Pageable;

interface ReceiptService
{
    function createReceipt(CreateReceiptBindingModel $bindingModel): Receipt;

    function editReceipt(Receipt $receipt, EditReceiptBindingModel $bindingModel): Receipt;

    function findAll(?Pageable $pageable, bool $showHidden = false): Page;

    function findOneById(int $id, bool $showHidden = false): ?Receipt;
}