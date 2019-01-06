<?php

namespace App\Twig;

use App\Entity\DefaultImage;
use App\Entity\General\NotFoundPage;
use Doctrine\ORM\EntityManagerInterface;
use Twig_Extension;
use Twig_Function;

class NotFoundPageDataExtension extends Twig_Extension
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * NotFoundPageDataExtension constructor.
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
            new Twig_Function('not_found_page_data', [$this, 'getNotFoundPageData']),
        );
    }

    public function getNotFoundPageData()
    {
        $page = $this->em->getRepository(NotFoundPage::class)->findAll()[0];

        return $page instanceof NotFoundPage ? $page : new NotFoundPage();

    }
}