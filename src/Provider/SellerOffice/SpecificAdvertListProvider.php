<?php

namespace App\Provider\SellerOffice;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\Client\Client;
use App\Type\AutoSparePartSpecificAdvertFilterType;
use Doctrine\ORM\EntityManagerInterface;

class SpecificAdvertListProvider
{
    /** @var EntityManagerInterface */
    private $em;

    const COUNT_ON_PAGE = 30;

    /**
     * SpecificAdvertListProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getBrands(Client $client)
    {
        $brands = $this->em->getRepository(AutoSparePartSpecificAdvert::class)->findAllIncludedBrandNames($client);

        $parsedBrands = [];

        foreach ($brands as $brand){
            $parsedBrands[$brand["id"]] = $brand["name"];
        }

        return $parsedBrands;
    }

    public function getModels(Client $client, Brand $brand)
    {
        $models = $this->em->getRepository(AutoSparePartSpecificAdvert::class)->findAllIncludedModelNamesByBrand($client, $brand);

        $parsedModels = [];

        foreach ($models as $model){
            $parsedModels[$model["id"]] = $model["name"];
        }

        return $parsedModels;
    }

    public function getSpareParts(Client $client)
    {
        $spareParts = $this->em->getRepository(AutoSparePartSpecificAdvert::class)->findAllIncludedSparePartNames($client);

        $parsedSpareParts = [];

        foreach ($spareParts as $sparePart){
            $parsedSpareParts[$sparePart["id"]] = $sparePart["name"];
        }

        return $parsedSpareParts;
    }

    public function getAdverts(AutoSparePartSpecificAdvertFilterType $filterType)
    {
        $adverts = $this->em->getRepository(AutoSparePartSpecificAdvert::class)->findAllByFilter($filterType);

        $parsedAdverts = [];

        /** @var AutoSparePartSpecificAdvert $advert */
        foreach ($adverts as $advert){
            $parsedAdverts[] = $advert->toArray();
        }

        return $parsedAdverts;
    }

    public function getCountPagesAdverts(AutoSparePartSpecificAdvertFilterType $filterType)
    {
        $count = (int)$this->em->getRepository(AutoSparePartSpecificAdvert::class)->findCountByFilter($filterType);

        $fullCount = intdiv($count, self::COUNT_ON_PAGE);

        if($count && $count % self::COUNT_ON_PAGE > 0){
            ++$fullCount;
        }

        return $fullCount;
    }
}