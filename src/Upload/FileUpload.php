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
    const USER = 'user';
    const BUSINESS_PROFILE = 'business_profile';
    const DEFAULT_IMAGE = "default";
    const AUTO_SPARE_PART_SPECIFIC_ADVERT = "auto-spare-part-specific-advert";

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

    public function uploadBase64Image($base64string)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64string, $type)) {
            $base64string = substr($base64string, strpos($base64string, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                // invalid image type
                return null;
            }

            $base64string = base64_decode($base64string);

            if ($base64string === false) {
                // base64_decode failed
                return null;
            }
        } else {
            // did not match data URI with image data
            return null;
        }

        $filename = sprintf('%s/%s/%s/%s.%s', $this->getFolder(), date('Y'), date('m'), uniqid(), $type);
        $folder = sprintf('%s/%s/%s', 'images/' . $this->getFolder(), date('Y'), date('m'));

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        file_put_contents('images/' . $filename, $base64string);

        return $filename;
    }
}
