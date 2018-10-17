<?php

namespace App\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gaufrette\Filesystem;

class FileUpload
{
    const GENERAL = 'general';
    const BRAND = 'brand';
    const MODEL = 'model';
    const CITY = 'city';
    const SPARE_PART = 'spare_part';
    const TMP = 'tmp';
    const DEFAULT_IMAGE = "default";

    const PHONE_BRAND = 'phone_brand';
    const PHONE_MODEL = 'phone_model';
    const PHONE_SPARE_PART = 'phone_spare_part';

    private static $allowedMimeTypes = array(
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif'
    );

    private $filesystem;

    private $folder;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function setFolder($folder){
        $this->folder = $folder;
    }

    public function getFolder(){
        return $this->folder ?: $folder = self::GENERAL;
    }

    public function upload(UploadedFile $file, $blob = null, $path = null)
    {
        if (!in_array($file->getClientMimeType(), self::$allowedMimeTypes)) {
            throw new \InvalidArgumentException(sprintf('Files of type %s are not allowed.', $file->getClientMimeType()));
        }

        if(is_null($path)){
            $filename = sprintf('%s/%s/%s/%s.%s', $this->getFolder(), date('Y'), date('m'), uniqid(), $file->getClientOriginalExtension());
        }
        else{
            $filename = $path;
        }

        $adapter = $this->filesystem->getAdapter();

        if($blob){
            $adapter->write($filename, $blob);
        }
        else{
            $adapter->write($filename, file_get_contents($file->getPathname()));
        }
        return $filename;
    }
}
