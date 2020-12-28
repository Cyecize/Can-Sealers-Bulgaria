<?php

namespace App\Service;

use App\BindingModel\CreateReceiptBindingModel;
use App\BindingModel\EditReceiptBindingModel;
use App\Entity\Receipt;
use App\Utils\ModelMapper;
use App\Utils\Page;
use App\Utils\Pageable;
use Doctrine\ORM\EntityManagerInterface;

class ReceiptServiceImpl implements ReceiptService
{

    private $entityManager;

    private $fileService;

    private $modelMapper;

    private $receiptRepo;

    public function __construct(EntityManagerInterface $entityManager,
                                ModelMapper $modelMapper,
                                FileService $fileService)
    {
        $this->entityManager = $entityManager;
        $this->modelMapper = $modelMapper;
        $this->fileService = $fileService;
        $this->receiptRepo = $this->entityManager->getRepository(Receipt::class);
    }

    function createReceipt(CreateReceiptBindingModel $bindingModel): Receipt
    {
        $receipt = $this->modelMapper->map($bindingModel, Receipt::class);

        $receipt->setImgPath($this->fileService->uploadProductImage($bindingModel->getImage()));

        $this->entityManager->persist($receipt);
        $this->entityManager->flush();
    }

    function editReceipt(Receipt $receipt, EditReceiptBindingModel $bindingModel): Receipt
    {
        // TODO: Implement editReceipt() method.
    }

    function findAll(Pageable $pageable, bool $showHidden = false): Page
    {
        return $this->receiptRepo->findAllPage($pageable, $showHidden);
    }

    public function findOneById(int $id): ?Receipt
    {
        return $this->receiptRepo->find($id);
    }
}