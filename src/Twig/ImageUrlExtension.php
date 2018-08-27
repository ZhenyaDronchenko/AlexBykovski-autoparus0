<?php

namespace App\Twig;

use Twig_Extension;
use Twig_Function;

class ImageUrlExtension extends Twig_Extension
{
    private $additionalPath;

    /**
     * ImageUrlExtension constructor.
     *
     * @param string $additionalPath
     */
    public function __construct($additionalPath)
    {
        $this->additionalPath = $additionalPath;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('image_url', [$this, 'imageUrlCreator']),
        );
    }

    public function imageUrlCreator($url)
    {
        return $this->additionalPath . $url;
    }
}