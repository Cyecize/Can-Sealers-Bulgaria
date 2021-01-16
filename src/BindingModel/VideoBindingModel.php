<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/5/2018
 * Time: 10:23 PM
 */

namespace App\BindingModel;

use Symfony\Component\Validator\Constraints as Assert;

class VideoBindingModel
{
    /**
     * @Assert\NotNull(message="Select video")
     * @Assert\File(
     *     maxSize="30M", maxSizeMessage="File size more than 30M",
     *     mimeTypes={"video/mp4"},
     *     mimeTypesMessage="Please upload a valid video type."
     * )
     */
    private $file;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }
}