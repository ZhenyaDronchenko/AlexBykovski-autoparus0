<?php

namespace App\Twig;

use App\Entity\DefaultImage;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Twig_Extension;
use Twig_Function;

class ImageUrlExtension extends Twig_Extension
{
    /** @var string */
    private $additionalPath;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * ImageUrlExtension constructor.
     *
     * @param string $additionalPath
     * @param EntityManagerInterface $em
     */
    public function __construct(string $additionalPath, EntityManagerInterface $em)
    {
        $this->additionalPath = $additionalPath;
        $this->em = $em;
    }

    public function getFunctions()
    {
        return [
            new Twig_Function('image_url', [$this, 'imageUrlCreator']),
        ];
    }

    public function imageUrlCreator($url, $defaultImageType = null)
    {
        $defaultImageId = array_key_exists($defaultImageType, DefaultImage::$defaultImages) ? DefaultImage::$defaultImages[$defaultImageType] : null;

        if($url instanceof Image){
            $url = $url->getImage();
        }

        if(!$url && $defaultImageId){
            $defaultImageObject = $this->em->getRepository(DefaultImage::class)->find($defaultImageId);

            $url = $defaultImageObject instanceof DefaultImage ? $defaultImageObject->getImage() : $url;
        }

        if(strpos($url, "https") === 0 || strpos($url, "http") === 0){
            return $url;
        }

        return $this->additionalPath . $url;
    }
}