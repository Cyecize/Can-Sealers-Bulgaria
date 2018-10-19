<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/19/2018
 * Time: 10:56 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Gallery;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class GalleryServiceImpl implements GalleryService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \AppBundle\Repository\GalleryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $galleryRepo;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->galleryRepo = $entityManager->getRepository(Gallery::class);
    }

    public function createIfNotExists(Product $product): Gallery
    {
        $gal = $this->findByProduct($product);
        if ($gal == null)
            $gal = $this->createGallery($product);
        return $gal;
    }

    public function findOneById(int $id): ?Gallery
    {
        return $this->galleryRepo->find($id);
    }

    public function findByProduct(Product $product): ?Gallery
    {
        return $this->galleryRepo->findOneBy(array('product' => $product));
    }

    //Private
    private function createGallery(Product $product): Gallery
    {
        $gal = new Gallery();
        $gal->setProduct($product);
        $this->entityManager->persist($gal);
        $this->entityManager->flush();
        return $gal;
    }
}