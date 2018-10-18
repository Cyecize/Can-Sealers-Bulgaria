<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 11:47 AM
 */

namespace AppBundle\Service;


use AppBundle\Exception\IllegalArgumentException;
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
     * @return string
     */
    public function uploadProductImage(UploadedFile $file) : string ;
}