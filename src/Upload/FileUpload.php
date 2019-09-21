<?php

namespace App\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gaufrette\Filesystem;
use WebPConvert\WebPConvert;

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
    const USER_OFFICE_GALLERY = "user-office-gallery";

    const GENERAL_MAIN_PAGE = "general-main-page";

    const PHONE_BRAND = 'phone_brand';
    const PHONE_MODEL = 'phone_model';
    const PHONE_SPARE_PART = 'phone_spare_part';

    const UNIVERSAL_PAGE_BRAND = 'universal-page-brand';
    const UNIVERSAL_PAGE_CITY = 'universal-page-city';
    const UNIVERSAL_PAGE_SPARE_PART = 'universal-page-spare-part';
    const IMPORT_SPECIFIC_ADVERT = 'import-specific-advert';
    const ARTICLE = 'article';

    const IMAGE_FOLDER = "images/";

    const CSV_MIME_TYPE = "text/csv";
    const EXCEL_MIME_TYPE = "application/vnd.ms-excel";
    const EXCEL_S_MIME_TYPE = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";

    private $allowedMimeTypes;

    private $filesystem;

    private $folder;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->allowedMimeTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp'
        ];
    }

    public function setAllowMimeTypes(array $types)
    {
        $this->allowedMimeTypes = $types;
    }

    public function setFolder($folder){
        $this->folder = $folder;
    }

    public function getFolder(){
        return $this->folder ?: $folder = self::GENERAL;
    }

    public function upload($file, $blob = null, $path = null)
    {
        if (is_string($file) && preg_match('/^data:image\/(\w+);base64,/', $file, $type)) {
            return $this->uploadBase64Image($file);
        }

        if (!($file instanceof UploadedFile) || !in_array($file->getClientMimeType(), $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException(sprintf('Files of type %s are not allowed...', $file->getClientMimeType()));
        }

        $filename = !is_null($path) ? $path : $this->getFilePath($file);

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
                throw new \InvalidArgumentException(sprintf('Files of type %s are not allowed.', $type));
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

        file_put_contents(self::IMAGE_FOLDER . $filename, $base64string);

        return $filename;
    }

    // https://github.com/rosell-dk/webp-convert/blob/master/docs/api/convert-and-serve.md
    static function fileToWebP($currentPath)
    {
        //$currentPath = self::IMAGE_FOLDER . $currentPath;
        $fileInfo = pathinfo($currentPath);

        $newPath = $fileInfo['dirname'] . '/' . $fileInfo["filename"] . ".webp";
        $fullNewPath = self::IMAGE_FOLDER . $newPath;

        WebPConvert::convert(self::IMAGE_FOLDER .  $currentPath, $fullNewPath, ["fail" => "serve-original"]);

        if(!file_exists($fullNewPath)){
            return $currentPath;
        }

        unlink(self::IMAGE_FOLDER . $currentPath);

        return $newPath;
    }

    public function getFilePath(UploadedFile $file, $additionalBeforeName = null)
    {
        $fileName = uniqid();

        if($additionalBeforeName){
            $fileName = $additionalBeforeName . '_' . $fileName;
        }

        return sprintf('%s/%s/%s/%s.%s', $this->getFolder(), date('Y'), date('m'), $fileName, $file->getClientOriginalExtension());
    }
}
