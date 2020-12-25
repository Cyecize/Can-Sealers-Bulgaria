<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/19/2018
 * Time: 10:48 AM
 */

namespace App\Service;

use App\BindingModel\ImageBindingModel;
use App\Entity\Gallery;
use App\Entity\Image;

interface ImageService
{
    /**
     * @param Image $image
     */
    public function removeImage(Image $image) : void ;

    /**
     * @param Gallery $gallery
     * @param ImageBindingModel $bindingModel
     * @param string $altMessage
     * @return Image
     */
    public function createImage(Gallery $gallery, ImageBindingModel $bindingModel, string $altMessage): Image;

    /**
     * @param int $id
     * @return Image|null
     */
    public function findOneById(int $id): ?Image;

    /**
     * @param Gallery $gallery
     * @return Image[]
     */
    public function findByGallery(Gallery $gallery): array;

    /**
     * @return Image[]
     */
    public function findAll(): array;
}