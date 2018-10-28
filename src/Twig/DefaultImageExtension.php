<?php

namespace App\Twig;

use App\Entity\DefaultImage;
use Doctrine\ORM\EntityManagerInterface;
use Twig_Extension;
use Twig_Function;

class DefaultImageExtension extends Twig_Extension
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * ShowCodeExtension constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('default_image', [$this, 'getDefaultImage']),
        );
    }

    public function getDefaultImage($id)
    {
        $defaultImage = $this->em->getRepository(DefaultImage::class)->find($id);

        return $defaultImage instanceof DefaultImage ? $defaultImage : new DefaultImage();

    }
}