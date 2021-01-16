<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 11:47 AM
 */

namespace App\Service;

use App\Exception\IllegalArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileService
{
    /**
     * @param string $dir
     * @throws IllegalArgumentException
     */
    public function removeDirectory(string $dir) : void ;

    /**
     * @param string $filePath
     */
    public function removeFile(string $filePath): void;

    /**
     * @param $file
     * @param string $path
     * @return string
     */
    public function uploadFile(UploadedFile $file, string $path): string;

    /**
     * @param UploadedFile $file
     * @return string - file path
     */
    public function uploadProductImage(UploadedFile $file) : string ;

    /**
     * @param UploadedFile $file
     * @return string - file path
     */
    public function uploadProductVideo(UploadedFile $file) : string ;

    /**
     * @param UploadedFile $file
     * @param int $galleryId
     * @return string
     */
    public function uploadGalleryImage(UploadedFile $file, int $galleryId) : string ;
}