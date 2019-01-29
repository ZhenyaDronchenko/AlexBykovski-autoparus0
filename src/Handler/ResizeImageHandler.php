<?php

namespace App\Handler;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\SparePart;

class ResizeImageHandler
{
    const IMAGE_FOLDER = "images/";

    const DEFAULT_HEIGHT = "150";
    const DEFAULT_WIDTH = "auto";
    const MODEL_HEIGHT = "64";
    const MODEL_WIDTH = "96";
    const SPARE_PART_HEIGHT = "64";
    const SPARE_PART_WIDTH = "96";

    const BRAND_HEIGHT_32 = "32";
    const BRAND_WIDTH_32 = "32";
    const BRAND_HEIGHT_64 = "64";
    const BRAND_WIDTH_64 = "64";

    const JPEG_TYPE = "image/jpeg";
    const PNG_TYPE = "image/png";
    const GIF_TYPE = "image/gif";
    const BMP_TYPE = "image/bmp";

    const MODEL_ADDITIONAL_PATH = "_thumb";
    const SPARE_PART_ADDITIONAL_PATH = "_thumb";

    static function hasImageFile($file)
    {
        return file_exists(self::IMAGE_FOLDER . $file);
    }

    static function resizeLogo($object, $newWidth = null, $newHeight = null)
    {
        if($object instanceof Model){
            return self::resizeImage($object->getLogo(), self::MODEL_WIDTH, self::MODEL_HEIGHT);
        }

        if($object instanceof SparePart){
            return self::resizeImage($object->getLogo(), self::SPARE_PART_WIDTH, self::SPARE_PART_HEIGHT);
        }

        if($object instanceof Brand){
            return self::resizeImage($object->getLogo(), $newWidth, $newHeight);
        }

        return "";
    }

    static function resizeImage($filePath, $newWidth, $newHeight)
    {
        $filePathFull = self::IMAGE_FOLDER . $filePath;
        $mimeType = self::getMimeType($filePathFull);

        list($width, $height) = getimagesize($filePathFull);

        $image_p = imagecreatetruecolor($newWidth, $newHeight);

        $image = self::createImageByMimeType($mimeType, $filePathFull);

        if(!$image){
            return null;
        }

        $newFilePath = self::getThumbnailFilePath($filePath, self::MODEL_ADDITIONAL_PATH);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        self::setImageByMimeType($mimeType, $image_p, self::IMAGE_FOLDER . $newFilePath);

        return $newFilePath;
    }

    static function getMimeType($filePath)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mimeType = finfo_file($finfo, $filePath);

        finfo_close($finfo);

        return $mimeType;
    }

    static function createImageByMimeType($mimeType, $filePath)
    {
        switch ($mimeType){
            case self::JPEG_TYPE:
                return imagecreatefromjpeg($filePath);
            case self::BMP_TYPE:
                return imagecreatefrombmp($filePath);
            case self::GIF_TYPE:
                return imagecreatefromgif($filePath);
            case self::PNG_TYPE:
                return imagecreatefrompng($filePath);
            default:
                return null;
        }
    }

    static function setImageByMimeType($mimeType, $resource, $filePath)
    {
        switch ($mimeType){
            case self::JPEG_TYPE:
                return imagejpeg($resource, $filePath, 100);
            case self::BMP_TYPE:
                return imagebmp($resource, $filePath);
            case self::GIF_TYPE:
                return imagegif($resource, $filePath);
            case self::PNG_TYPE:
                return imagepng($resource, $filePath);
            default:
                return null;
        }
    }

    static function getThumbnailFilePath($basePath, $addString)
    {
        $extension_pos = strrpos($basePath, '.'); // find position of the last dot, so where the extension starts

        return substr($basePath, 0, $extension_pos) . $addString . substr($basePath, $extension_pos);
    }
}