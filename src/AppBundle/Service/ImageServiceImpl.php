<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/19/2018
 * Time: 11:04 AM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Entity\Gallery;
use AppBundle\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;

class ImageServiceImpl implements ImageService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\ImageRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $imageRepo;

    /**
     * @var FileService
     */
    private $fileService;

    public function __construct(EntityManagerInterface $entityManager, FileService $fileService)
    {
        $this->entityManager = $entityManager;
        $this->imageRepo = $entityManager->getRepository(Image::class);
        $this->fileService = $fileService;
    }

    public function removeImage(Image $image): void
    {
        $this->fileService->removeFile(substr($image->getImageUrl(), 1));
        $this->entityManager->remove($image);
        $this->entityManager->flush();
    }

    public function createImage(Gallery $gallery, ImageBindingModel $bindingModel, string $altMessage): Image
    {
        $image = new Image();
        $image->setGallery($gallery);
        $image->setAltMessage($altMessage);
        $image->setImageUrl($this->fileService->uploadGalleryImage($bindingModel->getFile(), $gallery->getId()));
        $this->entityManager->persist($image);
        $this->entityManager->flush();
        return $image;
    }

    public function findOneById(int $id): ?Image
    {
        return $this->imageRepo->find($id);
    }

    public function findByGallery(Gallery $gallery): array
    {
        return $this->imageRepo->findBy(array('gallery'=>$gallery));
    }

    public function findAll(): array
    {
       return $this->imageRepo->findAll();
    }
}