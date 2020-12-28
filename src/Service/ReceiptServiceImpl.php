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
        $this->modelMapper->merge($bindingModel, $receipt, true);

        if ($bindingModel->getImage() != null) {
            $this->fileService->removeFile(substr($receipt->getImgPath(), 1));
            $receipt->setImgPath($this->fileService->uploadProductImage($bindingModel->getImage()));
        }
        return $this->save($receipt);
    }

    function findAll(Pageable $pageable, bool $showHidden = false): Page
    {
        return $this->receiptRepo->findAllPage($pageable, $showHidden);
    }

    public function findOneById(int $id, bool $showHidden = false): ?Receipt
    {
        if ($showHidden) return $this->receiptRepo->find($id);
        return $this->receiptRepo->findOneBy(array('id' => $id, 'hidden' => false));
    }

    private function save(Receipt $receipt): Receipt
    {
        $this->entityManager->merge($receipt);
        $this->entityManager->flush();
        return $receipt;
    }
}