<?php

namespace App\Helper;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Handler\ResizeImageHandler;

class AdminHelper
{
    private $uploadDirectory;

    /**
     * AdminHelper constructor.
     * @param string $uploadDirectory
     */
    public function __construct($uploadDirectory)
    {
        $this->uploadDirectory = $uploadDirectory;
    }

    public function getImagesHelp(array $images){
        $help = "";

        if(count($images)){
            $help .= '<div class="row">';

            foreach($images as $image){
                $help .= $this->getSingleImageHelp($image);
            }

            $help .= '</div>';
        }

        return $help;
    }

    protected function getSingleImageHelp($image){
        if(is_array($image)){
            $height = is_numeric($image["height"]) ? $image["height"] . "px" : $image["height"];
            $width = is_numeric($image["width"]) ? $image["width"] . "px" : $image["width"];

            return '<div class="col-md-4">
                    <img class="thumbnail" src="' . $this->uploadDirectory . $image["image"] . '"
                     alt="Lights" style="height: ' . $height . '; width: ' . $width . ';">
                </div>';
        }

        return '<div class="col-md-4">
                    <img class="thumbnail" src="' . $this->uploadDirectory . $image . '" alt="Lights" style="height: 150px;">
                </div>';
    }

    static function getHelpImages($object)
    {
        switch (get_class($object)){
            case Model::class:
                return self::getModelHelpImages($object);
            case SparePart::class:
                return self::getSparePartHelpImages($object);
            case Brand::class:
                return self::getBrandHelpImages($object);
            default:
                return [];
        }
    }

    static function getModelHelpImages(Model $model)
    {
        return [
            [
                "image" => $model->getLogo(),
                "width" => ResizeImageHandler::DEFAULT_WIDTH,
                "height" => ResizeImageHandler::DEFAULT_HEIGHT,
            ],
            [
                "image" => $model->getThumbnailLogo(),
                "width" => ResizeImageHandler::MODEL_WIDTH,
                "height" => ResizeImageHandler::MODEL_HEIGHT,
            ]
        ];
    }

    static function getSparePartHelpImages(SparePart $sparePart)
    {
        return [
            [
                "image" => $sparePart->getLogo(),
                "width" => ResizeImageHandler::DEFAULT_WIDTH,
                "height" => ResizeImageHandler::DEFAULT_HEIGHT,
            ],
            [
                "image" => $sparePart->getThumbnailLogo(),
                "width" => ResizeImageHandler::SPARE_PART_WIDTH,
                "height" => ResizeImageHandler::SPARE_PART_HEIGHT,
            ]
        ];
    }

    static function getBrandHelpImages(Brand $brand)
    {
        return [
            [
                "image" => $brand->getLogo(),
                "width" => ResizeImageHandler::DEFAULT_WIDTH,
                "height" => ResizeImageHandler::DEFAULT_HEIGHT,
            ],
            [
                "image" => $brand->getThumbnailLogo64(),
                "width" => ResizeImageHandler::BRAND_WIDTH_64,
                "height" => ResizeImageHandler::BRAND_HEIGHT_64,
            ],
            [
                "image" => $brand->getThumbnailLogo32(),
                "width" => ResizeImageHandler::BRAND_WIDTH_32,
                "height" => ResizeImageHandler::BRAND_HEIGHT_32,
            ]
        ];
    }
}