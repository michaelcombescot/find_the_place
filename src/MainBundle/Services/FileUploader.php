<?php

namespace MainBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $path = 'uploads/file/'.$fileName;
        $file->move($this->targetDir, $fileName);
        return $path;
    }
}