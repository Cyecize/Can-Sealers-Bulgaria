<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 11:49 AM
 */

namespace AppBundle\Service;

use AppBundle\Constants\Config;
use AppBundle\Exception\IllegalArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileServiceImpl implements FileService
{
    public function __construct()
    {

    }

    public function removeDirectory(string $dirPath): void
    {
        if (strpos($dirPath, '../') != false)
            throw new IllegalArgumentException("Hacker!");

        if (!is_dir($dirPath)) {
            if (file_exists($dirPath) !== false) {
                unlink($dirPath);
            }
            return;
        }

        if ($dirPath[strlen($dirPath) - 1] != '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->removeDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function removeFile(string $filePath): void
    {
        if (file_exists($filePath))
            unlink($filePath);
    }

    public function uploadFile(UploadedFile $file, string $path): string
    {
        $imgName = md5($file->getFilename()) . time() . "." . $file->guessExtension();
        $file->move($path, $imgName);
        return $imgName;
    }

    public function uploadProductImage(UploadedFile $file): string
    {
        $fileName = $this->uploadFile($file, Config::PRODUCT_FILES_PATH);
        return "/" . Config::PRODUCT_FILES_PATH . $fileName;
    }
}