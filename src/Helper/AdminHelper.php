<?php

namespace App\Helper;

use App\Entity\Article\ArticleBanner;
use App\Entity\Article\ArticleImage;
use App\Entity\Brand;
use App\Entity\Image;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\UniversalPage\UniversalPage;
use App\Entity\UniversalPage\UniversalPageBrand;
use App\Entity\UniversalPage\UniversalPageCity;
use App\Entity\UniversalPage\UniversalPageSparePart;
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

    static function getImagesData($object)
    {
        switch (str_replace('Proxies\\__CG__\\', "", get_class($object))){
            case Model::class:
                return self::getModelImages($object);
            case SparePart::class:
                return self::getSparePartImages($object);
            case Brand::class:
                return self::getBrandImages($object);
            case UniversalPageSparePart::class:
            case UniversalPageBrand::class:
            case UniversalPageCity::class:
                return self::getUniversalPageImages($object);
            case ArticleImage::class:
                return self::getArticleImageImages($object);
            case ArticleBanner::class:
                return self::getArticleBannerImages($object);
            default:
                return [];
        }
    }

    static function getModelImages(Model $model)
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

    static function getSparePartImages(SparePart $sparePart)
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

    static function getBrandImages(Brand $brand)
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

    static function getUniversalPageImages(UniversalPage $page)
    {
        $images = [];

        /** @var Image $image */
        foreach ($page->getImages() as $image){
            $images[] = [
                "image" => $image->getImage(),
                "width" => ResizeImageHandler::DEFAULT_WIDTH,
                "height" => ResizeImageHandler::DEFAULT_HEIGHT,
            ];
        }

        return $images;
    }

    static function getArticleImageImages(ArticleImage $image)
    {
        return [
            [
                "image" => $image->getImage(),
                "width" => ResizeImageHandler::DEFAULT_WIDTH,
                "height" => ResizeImageHandler::DEFAULT_HEIGHT,
            ],
            [
                "image" => $image->getImageThumbnail(),
                "width" => ResizeImageHandler::DEFAULT_WIDTH,
                "height" => ResizeImageHandler::DEFAULT_HEIGHT / 2,
            ],
        ];
    }

    static function getArticleBannerImages(ArticleBanner $banner)
    {
        return [
            [
                "image" => $banner->getImage(),
                "width" => ResizeImageHandler::DEFAULT_WIDTH,
                "height" => ResizeImageHandler::DEFAULT_HEIGHT,
            ],
        ];
    }
}