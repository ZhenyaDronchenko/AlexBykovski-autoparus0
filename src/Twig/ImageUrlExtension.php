<?php

namespace App\Twig;

use App\Entity\DefaultImage;
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
    public function __construct($additionalPath, EntityManagerInterface $em)
    {
        $this->additionalPath = $additionalPath;
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('image_url', [$this, 'imageUrlCreator']),
        );
    }

    public function imageUrlCreator($url, $defaultImageType = null)
    {
        $defaultImageId = array_key_exists($defaultImageType, DefaultImage::$defaultImages) ? DefaultImage::$defaultImages[$defaultImageType] : null;

        if(!$url && $defaultImageId){
            $defaultImageObject = $this->em->getRepository(DefaultImage::class)->find($defaultImageId);

            $url = $defaultImageObject instanceof DefaultImage ? $defaultImageObject->getImage() : $url;
        }

        return $this->additionalPath . $url;
    }
}