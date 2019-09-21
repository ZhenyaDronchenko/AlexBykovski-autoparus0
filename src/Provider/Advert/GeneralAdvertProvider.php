<?php

namespace App\Provider\Advert;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Type\CatalogAdvertFilterType;
use Doctrine\ORM\EntityManagerInterface;

class GeneralAdvertProvider
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * SpecificAdvertProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function provideSortedListAdverts(CatalogAdvertFilterType $catalogFilter)
    {
        $catalogFilter->setLimit(10);

        $generalAdvertsBrand = $this->em->getRepository(AutoSparePartGeneralAdvert::class)->findAllForCatalog($catalogFilter, true);

        $catalogFilter->setLimit(5);

        $generalAdvertsWithoutBrand = $this->em->getRepository(AutoSparePartGeneralAdvert::class)->findAllForCatalog($catalogFilter, false);

        return array_merge($generalAdvertsBrand, $generalAdvertsWithoutBrand);
    }
}